<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class UpdateDocumentRequest extends FormRequest
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
            'file' => ['sometimes', File::types('pdf')->min('1kb')->max('10mb')],
            'file_name' => ['sometimes', 'required_with:file', 'string', 'max:100', 'regex:/^[\w\-. ]+$/'],
        ];
    }
}
