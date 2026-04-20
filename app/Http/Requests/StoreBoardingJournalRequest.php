<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBoardingJournalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'journal_date'    => ['required', 'date'],
            'condition'       => ['required', 'in:good,normal,stressed,sick'],
            'eating_notes'    => ['nullable', 'string', 'max:500'],
            'activity_notes'  => ['nullable', 'string', 'max:500'],
            'health_notes'    => ['nullable', 'string', 'max:500'],
            'photo'           => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }
}
