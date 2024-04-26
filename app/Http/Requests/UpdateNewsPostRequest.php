<?php

namespace App\Http\Requests;

use App\Models\NewsPost;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNewsPostRequest extends FormRequest
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
            'slug' => ['required', 'string', 'regex:/^[\-\w_]+$/', Rule::unique(NewsPost::class)->ignore($this->post->id)],
            'author_id' => ['required', 'ulid', 'exists:users,id'],
            'body' => ['required', 'string'],
        ];
    }
}
