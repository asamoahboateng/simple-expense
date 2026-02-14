<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Expense Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #1f2937; }
        h1 { font-size: 20px; margin-bottom: 5px; }
        .meta { color: #6b7280; margin-bottom: 20px; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #f3f4f6; font-weight: bold; text-align: left; padding: 8px 10px; border-bottom: 2px solid #d1d5db; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: #374151; }
        td { padding: 7px 10px; border-bottom: 1px solid #e5e7eb; }
        tr:nth-child(even) { background-color: #f9fafb; }
        .amount { text-align: right; font-variant-numeric: tabular-nums; }
        .total-row { border-top: 2px solid #374151; }
        .total-row td { font-weight: bold; padding-top: 10px; }
        .footer { margin-top: 30px; font-size: 10px; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>
    <h1>Expense Report</h1>
    <p class="meta">Period: {{ $dateFrom }} to {{ $dateTo }} &bull; Generated: {{ now()->format('Y-m-d H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Title</th>
                <th>Category</th>
                <th>Person</th>
                <th class="amount">Amount (GHS)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($expenses as $expense)
            <tr>
                <td>{{ $expense->expense_date->format('Y-m-d') }}</td>
                <td>{{ $expense->title }}</td>
                <td>{{ $expense->mainCategory?->name ?? 'Uncategorized' }}</td>
                <td>{{ $expense->person }}</td>
                <td class="amount">{{ number_format($expense->cost, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; color: #9ca3af; padding: 20px;">No expenses found for this period.</td>
            </tr>
            @endforelse
        </tbody>
        @if($expenses->count())
        <tfoot>
            <tr class="total-row">
                <td colspan="4" style="text-align: right;">Total:</td>
                <td class="amount">GHS {{ number_format($total, 2) }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        Expense Tracker &mdash; Report generated automatically
    </div>
</body>
</html>
