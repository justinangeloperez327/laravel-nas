<?php

namespace App\Modules\Organization\Http\Requests;

use App\Modules\Organization\Models\Section;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(
            $this->isMethod('post') ? 'organization.create' : 'organization.update',
        ) ?? false;
    }

    /**
     * @return array<string,mixed>
     */
    public function rules(): array
    {
        /** @var Section|null $section */
        $section = $this->route('section');
        $departmentId = $this->integer('department_id');

        return [
            'department_id' => ['required', 'integer', 'exists:departments,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('sections', 'code')
                    ->where(fn ($query) => $query->where('department_id', $departmentId))
                    ->ignore($section?->id),
            ],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
