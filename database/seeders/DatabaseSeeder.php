<?php

namespace Database\Seeders;

use App\Modules\Approvals\Database\Seeders\ApprovalAccessSeeder;
use App\Modules\Clients\Database\Seeders\ClientAccessSeeder;
use App\Modules\Contracts\Database\Seeders\ContractAccessSeeder;
use App\Modules\DocumentControl\Database\Seeders\DocumentControlAccessSeeder;
use App\Modules\Organization\Database\Seeders\OrganizationAccessSeeder;
use App\Modules\Projects\Database\Seeders\ProjectAccessSeeder;
use App\Modules\Suppliers\Database\Seeders\SupplierAccessSeeder;
use App\Modules\Users\Database\Seeders\UserAccessSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserAccessSeeder::class,
            OrganizationAccessSeeder::class,
            ClientAccessSeeder::class,
            ProjectAccessSeeder::class,
            ContractAccessSeeder::class,
            ApprovalAccessSeeder::class,
            DocumentControlAccessSeeder::class,
            SupplierAccessSeeder::class,
        ]);
    }
}
