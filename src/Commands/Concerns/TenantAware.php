<?php

namespace Spatie\Multitenancy\Commands\Concerns;

use Illuminate\Support\Arr;
use Spatie\Multitenancy\Concerns\UsesMultitenancyConfig;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

trait TenantAware
{
    use UsesMultitenancyConfig,
        UsesTenantModel;

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tenants = Arr::wrap($this->option('tenant'));

        $tenantQuery = $this->getTenantModel()::query()
            ->when(! blank($tenants), function ($query) use ($tenants) {
                collect($this->getTenantArtisanSearchFields())
                    ->each(function ($field) use ($query, $tenants) { return $query->orWhereIn($field, Arr::wrap($tenants)); });
            });

        if ($tenantQuery->count() === 0) {
            $this->error('No tenant(s) found.');

            return -1;
        }

        return $tenantQuery
            ->cursor()
            ->map(function ($tenant) { $tenant->execute(function () { return (int) $this->laravel->call([$this, 'handle']); }); })
            ->sum();
    }
}
