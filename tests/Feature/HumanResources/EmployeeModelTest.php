<?php

namespace Tests\Feature\HumanResources;

use App\Modules\HumanResources\Models\Employee;
use App\Modules\Organization\Models\Company;
use App\Modules\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_and_user_are_separate_records(): void
    {
        $company = Company::query()->create(['name' => 'NAS', 'code' => 'NAS', 'is_active' => true]);
        $user = User::factory()->create();

        $employee = Employee::query()->create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'employee_number' => 'EMP-001',
            'first_name' => 'Example',
            'last_name' => 'Employee',
            'status' => 'active',
        ]);

        $this->assertSame($user->id, $employee->user_id);
        $this->assertSame('EMP-001', $employee->employee_number);
    }
}
