<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StoreDocumentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:100'],
            'category' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'file' => ['required', File::types('pdf')->min('1kb')->max('10mb')],
            'file_name' => ['required', 'string', 'max:100', 'regex:/^[\w\-. ]+$/'],
        ];
    }
}
