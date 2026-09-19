<?php

use App\Modules\Approvals\ApprovalsServiceProvider;
use App\Modules\Clients\ClientsServiceProvider;
use App\Modules\Contracts\ContractsServiceProvider;
use App\Modules\DocumentControl\DocumentControlServiceProvider;
use App\Modules\Organization\OrganizationServiceProvider;
use App\Modules\Procurement\ProcurementServiceProvider;
use App\Modules\Projects\ProjectsServiceProvider;
use App\Modules\Suppliers\SuppliersServiceProvider;
use App\Modules\Users\UsersServiceProvider;
use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    ApprovalsServiceProvider::class,
    ClientsServiceProvider::class,
    ContractsServiceProvider::class,
    DocumentControlServiceProvider::class,
    OrganizationServiceProvider::class,
    ProjectsServiceProvider::class,
    ProcurementServiceProvider::class,
    SuppliersServiceProvider::class,
    UsersServiceProvider::class,
];
