<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrayerRequest extends FormRequest
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
            'date' => [
                'required',
                'date'
            ],
            'fajr' => [
                'required',
                'date_format:H:i'
            ],
            'zuhr' => [
                'required',
                'date_format:H:i'
            ],
            'asr' => [
                'required',
                'date_format:H:i'
            ],
            'maghreb' => [
                'required',
                'date_format:H:i'
            ],
            'isha' => [
                'required',
                'date_format:H:i'
            ],
        ];
    }
}
