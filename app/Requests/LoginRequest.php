<?php

namespace App\Requests;

use System\Http\FormRequest;

class LoginRequest extends FormRequest
{
    protected static array $messages = [
        'email.required'    => 'Пожалуйста, укажите свой Login',
        'password.required' => 'Пожалуйста, введите пароль',
    ];

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'login'    => 'required',
            'password' => 'required',
        ];
    }
}
