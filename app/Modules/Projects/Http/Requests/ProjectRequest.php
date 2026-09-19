<?php

namespace App\Modules\Projects\Http\Requests;

use App\Modules\Projects\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can($this->isMethod('post') ? 'projects.create' : 'projects.update') ?? false;
    }

    public function rules(): array
    {
        /** @var Project|null $project */
        $project = $this->route('project');
        $companyId = $this->integer('company_id');

        return [
            'client_id' => ['required', 'integer', 'exists:clients,id'],
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'business_unit_id' => ['nullable', 'integer', Rule::exists('business_units', 'id')->where('company_id', $companyId)],
            'location_id' => ['nullable', 'integer', Rule::exists('locations', 'id')->where('company_id', $companyId)],
            'project_number' => ['required', 'string', 'max:100', Rule::unique('projects', 'project_number')->ignore($project?->id)],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['planned', 'active', 'on-hold', 'completed', 'cancelled'])],
            'start_date' => ['nullable', 'date'],
            'planned_completion_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'actual_completion_date' => ['nullable', 'date'],
            'contract_value' => ['nullable', 'numeric', 'min:0'],
            'progress_percentage' => ['required', 'numeric', 'between:0,100'],
        ];
    }
}
