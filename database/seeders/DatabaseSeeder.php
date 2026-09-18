<?php

namespace Database\Seeders;

use App\Modules\Users\Database\Seeders\UserAccessSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserAccessSeeder::class,
        ]);
    }
}
