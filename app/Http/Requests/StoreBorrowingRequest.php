<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBorrowingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'room_id'     => 'nullable|exists:rooms,id',
            'start_date'  => 'required|date|after:now',
            'end_date'    => 'required|date|after:start_date',
            'notes'       => 'nullable|string',
            'equipment'   => 'nullable|array',
            'equipment.*.selected' => 'nullable|in:1,on,true,0,off,false',
            'equipment.*.quantity' => 'nullable|integer|min:1',
        ];
    }
}
