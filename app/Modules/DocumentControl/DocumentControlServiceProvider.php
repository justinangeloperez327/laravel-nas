<?php

namespace App\Modules\DocumentControl;

use Illuminate\Support\ServiceProvider;

class DocumentControlServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
    }
}
