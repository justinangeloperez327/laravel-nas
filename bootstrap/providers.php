<?php

use App\Modules\Clients\ClientsServiceProvider;
use App\Modules\Contracts\ContractsServiceProvider;
use App\Modules\Organization\OrganizationServiceProvider;
use App\Modules\Projects\ProjectsServiceProvider;
use App\Modules\Users\UsersServiceProvider;
use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    ClientsServiceProvider::class,
    ContractsServiceProvider::class,
    OrganizationServiceProvider::class,
    ProjectsServiceProvider::class,
    UsersServiceProvider::class,
];
