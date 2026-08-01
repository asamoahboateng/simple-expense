<?php

namespace App\Services;

use App\Exceptions\InsufficientStockException;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockLot;
use App\Models\StockLotConsumption;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public static function recordPurchase(
        Product $product,
        int $quantity,
        float $unitCost,
        ?string $supplier = null,
        ?string $note = null,
        ?int $recordedBy = null,
        ?string $purchasedAt = null,
    ): StockLot {
        return DB::transaction(function () use ($product, $quantity, $unitCost, $supplier, $note, $recordedBy, $purchasedAt) {
            $lot = StockLot::create([
                'product_id' => $product->id,
                'batch_number' => self::nextBatchNumber($product),
                'quantity' => $quantity,
                'quantity_remaining' => $quantity,
                'unit_cost' => $unitCost,
                'supplier' => $supplier,
                'note' => $note,
                'purchased_at' => $purchasedAt ?? now()->toDateString(),
                'recorded_by' => $recordedBy,
            ]);

            $product->increment('quantity_on_hand', $quantity);

            return $lot;
        });
    }

    public static function checkout(array $cartItems, ?string $customerName, ?int $soldBy): Sale
    {
        return DB::transaction(function () use ($cartItems, $customerName, $soldBy) {
            $sale = Sale::create([
                'sale_number' => self::nextSaleNumber(),
                'customer_name' => $customerName,
                'sold_by' => $soldBy,
                'total_amount' => 0,
                'total_cost' => 0,
                'status' => 'completed',
                'sold_at' => now(),
            ]);

            $totalAmount = 0;
            $totalCost = 0;

            foreach ($cartItems as $item) {
                $product = Product::findOrFail($item['product_id']);
                $quantity = (int) $item['quantity'];
                $unitPrice = (float) $item['unit_price'];

                [$lineCost, $consumptions] = self::consumeFifo($product, $quantity);

                $saleItem = SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'unit_cost' => $quantity > 0 ? round($lineCost / $quantity, 2) : 0,
                    'line_total' => round($unitPrice * $quantity, 2),
                    'line_cost' => round($lineCost, 2),
                ]);

                foreach ($consumptions as $consumption) {
                    StockLotConsumption::create([
                        'sale_item_id' => $saleItem->id,
                        'stock_lot_id' => $consumption['lot']->id,
                        'quantity' => $consumption['quantity'],
                        'unit_cost' => $consumption['lot']->unit_cost,
                    ]);
                }

                $totalAmount += $saleItem->line_total;
                $totalCost += $saleItem->line_cost;
            }

            $sale->update([
                'total_amount' => round($totalAmount, 2),
                'total_cost' => round($totalCost, 2),
            ]);

            return $sale->fresh('items');
        });
    }

    public static function voidSale(Sale $sale): void
    {
        if ($sale->status === 'voided') {
            return;
        }

        DB::transaction(function () use ($sale) {
            foreach ($sale->items()->with('consumptions.stockLot')->get() as $item) {
                foreach ($item->consumptions as $consumption) {
                    $consumption->stockLot->increment('quantity_remaining', $consumption->quantity);
                    $consumption->stockLot->product->increment('quantity_on_hand', $consumption->quantity);
                }
            }

            $sale->update(['status' => 'voided']);
        });
    }

    /**
     * Consume stock from a product's lots oldest-first, returning the total cost
     * and a list of ['lot' => StockLot, 'quantity' => int] describing what was drawn.
     */
    private static function consumeFifo(Product $product, int $quantity): array
    {
        if ($quantity <= 0) {
            return [0.0, []];
        }

        $lots = StockLot::where('product_id', $product->id)
            ->where('quantity_remaining', '>', 0)
            ->orderBy('purchased_at')
            ->orderBy('id')
            ->lockForUpdate()
            ->get();

        $remainingToConsume = $quantity;
        $totalCost = 0.0;
        $consumptions = [];

        foreach ($lots as $lot) {
            if ($remainingToConsume <= 0) {
                break;
            }

            $take = min($lot->quantity_remaining, $remainingToConsume);
            $lot->decrement('quantity_remaining', $take);

            $totalCost += $take * (float) $lot->unit_cost;
            $consumptions[] = ['lot' => $lot, 'quantity' => $take];
            $remainingToConsume -= $take;
        }

        if ($remainingToConsume > 0) {
            throw new InsufficientStockException("Not enough stock for \"{$product->name}\" — short by {$remainingToConsume} {$product->unit}.");
        }

        $product->decrement('quantity_on_hand', $quantity);

        return [$totalCost, $consumptions];
    }

    private static function nextSaleNumber(): string
    {
        $lastId = (int) (Sale::max('id') ?? 0);

        return 'SALE-'.str_pad((string) ($lastId + 1), 6, '0', STR_PAD_LEFT);
    }

    private static function nextBatchNumber(Product $product): string
    {
        $count = StockLot::where('product_id', $product->id)->count();

        return sprintf('%s-B%03d', $product->sku, $count + 1);
    }
}
