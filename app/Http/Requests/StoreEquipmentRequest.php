<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEquipmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'category'    => 'required|string',
            'stock'       => 'required|integer|min:0',
            // 'condition'   => 'nullable',
            // 'status'      => 'nullable',
            'image'       => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ];
    }
}
