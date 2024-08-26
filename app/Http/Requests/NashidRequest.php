<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NashidRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'title' => [
                'required',
                'string'
            ],
            'url' => [
                'required',
                'file',
            ]
        ];
    }
}
