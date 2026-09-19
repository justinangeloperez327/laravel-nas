<?php

namespace Database\Seeders;

use App\Modules\Organization\Database\Seeders\OrganizationAccessSeeder;
use App\Modules\Users\Database\Seeders\UserAccessSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserAccessSeeder::class,
            OrganizationAccessSeeder::class,
        ]);
    }
}
