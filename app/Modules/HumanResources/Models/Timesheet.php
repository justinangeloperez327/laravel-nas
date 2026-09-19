<?php

namespace App\Modules\HumanResources\Models;

use App\Modules\Projects\Models\Project;
use App\Modules\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Timesheet extends Model
{
    protected $fillable = ['employee_id', 'project_id', 'approved_by_user_id', 'work_date', 'hours', 'overtime_hours', 'activity', 'status'];

    protected function casts(): array
    {
        return ['work_date' => 'date', 'hours' => 'decimal:2', 'overtime_hours' => 'decimal:2'];
    }

    public function employee(): BelongsTo { return $this->belongsTo(Employee::class); }
    public function project(): BelongsTo { return $this->belongsTo(Project::class); }
    public function approver(): BelongsTo { return $this->belongsTo(User::class, 'approved_by_user_id'); }
}
