<?php

namespace App\Livewire\Migration;

use App\Services\MigrationImportService;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Server Migration')]
class PullFromServer extends Component
{
    public string $old_server_url = '';

    public function pullFromOldServer(): void
    {
        $this->validate([
            'old_server_url' => ['required', 'url'],
        ]);

        $url = rtrim($this->old_server_url, '/');
        $secret = config('services.migration.secret');

        if (blank($secret)) {
            $this->addError('old_server_url', 'MIGRATION_SECRET is not configured on this server.');
            return;
        }

        $response = Http::withHeaders(['X-Migration-Secret' => $secret])
            ->timeout(120)
            ->get("{$url}/server-migration/export");

        if (! $response->successful()) {
            $this->addError('old_server_url', 'Old server rejected the request — check MIGRATION_SECRET matches on both servers.');
            return;
        }

        try {
            $summary = MigrationImportService::import($response->json(), parse_url($url, PHP_URL_HOST));
        } catch (\Throwable $e) {
            $this->addError('old_server_url', 'Import failed: '.$e->getMessage());
            return;
        }

        Notification::make()
            ->title('Migration complete')
            ->body("Users: {$summary['users']}, Main categories: {$summary['main_categories']}, Subcategories: {$summary['sub_categories']}, Expenses: {$summary['expenses']}")
            ->success()
            ->send();
    }

    public function render()
    {
        return view('livewire.migration.pull-from-server');
    }
}
