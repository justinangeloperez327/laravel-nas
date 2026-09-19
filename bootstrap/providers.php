<?php

use App\Modules\Organization\OrganizationServiceProvider;
use App\Modules\Users\UsersServiceProvider;
use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    OrganizationServiceProvider::class,
    UsersServiceProvider::class,
];
