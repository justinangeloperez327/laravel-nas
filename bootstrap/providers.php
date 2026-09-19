<?php

use App\Modules\Clients\ClientsServiceProvider;
use App\Modules\Organization\OrganizationServiceProvider;
use App\Modules\Projects\ProjectsServiceProvider;
use App\Modules\Users\UsersServiceProvider;
use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    ClientsServiceProvider::class,
    OrganizationServiceProvider::class,
    ProjectsServiceProvider::class,
    UsersServiceProvider::class,
];
