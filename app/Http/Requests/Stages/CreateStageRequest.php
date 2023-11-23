<?php

declare(strict_types=1);

namespace App\Http\Requests\Stages;

use App\Models\Stage;
use Illuminate\Foundation\Http\FormRequest;

class CreateStageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('create', Stage::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:20',
            'hex_color' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'order' => 'required|integer',
            'is_final_stage' => 'sometimes|boolean',
        ];
    }
}
