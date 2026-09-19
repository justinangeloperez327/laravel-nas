<?php

namespace App\Modules\HumanResources\Models;

use App\Modules\Organization\Models\Company;
use App\Modules\Organization\Models\Department;
use App\Modules\Organization\Models\Position;
use App\Modules\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $fillable = [
        'user_id', 'company_id', 'department_id', 'position_id', 'employee_number',
        'first_name', 'last_name', 'email', 'phone', 'nationality', 'hire_date',
        'employment_type', 'status',
    ];

    protected function casts(): array
    {
        return ['hire_date' => 'date'];
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function company(): BelongsTo { return $this->belongsTo(Company::class); }
    public function department(): BelongsTo { return $this->belongsTo(Department::class); }
    public function position(): BelongsTo { return $this->belongsTo(Position::class); }
    public function projectAssignments(): HasMany { return $this->hasMany(EmployeeProjectAssignment::class); }
    public function documents(): HasMany { return $this->hasMany(EmployeeDocument::class); }
    public function certifications(): HasMany { return $this->hasMany(EmployeeCertification::class); }
}
