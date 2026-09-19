<?php

namespace App\Modules\Procurement;

use Illuminate\Support\ServiceProvider;

class ProcurementServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
    }
}
