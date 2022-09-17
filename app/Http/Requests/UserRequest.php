<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'email' => ['required', 'string', 'email'],
            'bio' => ['nullable', 'string'],
            'profile_photo' => ['nullable', 'url'],
            'website_url' => ['nullable', 'url'],
            'facebook_url' => ['nullable', 'url'],
            'twitter_url' => ['nullable', 'url'],
            'telegram_url' => ['nullable', 'url'],
        ];
    }

}
