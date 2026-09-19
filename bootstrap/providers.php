<?php

use App\Modules\Clients\ClientsServiceProvider;
use App\Modules\Organization\OrganizationServiceProvider;
use App\Modules\Users\UsersServiceProvider;
use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    ClientsServiceProvider::class,
    OrganizationServiceProvider::class,
    UsersServiceProvider::class,
];
