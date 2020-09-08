<?php

namespace Spatie\Multitenancy\Tests\Feature\Tasks\TestClasses;

use Illuminate\Cache\Repository;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;

class DummyTask implements SwitchTenantTask
{
    public $config;

    public $a;

    public $b;

    public $madeCurrentCalled = false;

    public $forgotCurrentCalled = false;

    public function __construct(Repository $config, int $a = 0, int $b = 0)
    {
        $this->config = $config;
        $this->a = $a;
        $this->b = $b;
    }

    public function makeCurrent(Tenant $tenant): void
    {
        $this->madeCurrentCalled = true;
    }

    public function forgetCurrent(): void
    {
        $this->forgotCurrentCalled = false;
    }
}
