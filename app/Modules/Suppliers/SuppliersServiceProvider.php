<?php

namespace App\Modules\Suppliers;

use Illuminate\Support\ServiceProvider;

class SuppliersServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
    }
}
