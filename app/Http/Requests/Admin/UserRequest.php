<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $acceptedId = $this->id ?? '';
        return [
            "name"  => "required",
            "email" => "required|unique:users,email," . $acceptedId,
            "phone" => "required|numeric|unique:users,phone," . $acceptedId,
            "identity" => "required|numeric|unique:users,identity," . $acceptedId,
            "status"    => "required|numeric",
            'password' => $acceptedId ? 'nullable':'required'.'|same:confirm_password|min:6',
            'confirm_password' => $acceptedId ? 'nullable':'required|min:6',
        ];
    }
    public function messages()
    {
        return [
            "name.required" => "name is required",
            "status.required" => "status is required",
            "status.numeric" => "status is invalid format",
            "email.required" => "email is required",
            "email.unique" => "Email already exists",
            "phone.unique" => "Phone number already exists",
            "phone.required" => "phone is required",
            "phone.numeric" => "phone is invalid format",
            "identity.unique" => "Identity already exists",
            "identity.required" => "identity is required",
            "identity.numeric" => "identity is invalid format",
            "password" => "Your password is too short, Must be 8 or more characters",
            'password.required' => "Password is required",
            'password.same' => "The password not match confirm password",
            'confirm_password.required' => "Confirm Password is required",
        ];
    }
}
