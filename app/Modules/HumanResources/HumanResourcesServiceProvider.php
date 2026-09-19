<?php

namespace App\Modules\HumanResources;

use Illuminate\Support\ServiceProvider;

class HumanResourcesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
    }
}
