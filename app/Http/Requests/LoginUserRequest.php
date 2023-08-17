<?php

namespace App\Http\Requests;

use App\Models\User;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class LoginUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['regex:/^[\w\._\-\d]+$/i'],
            'password' => ['required', 'min:8'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (isset($this->username_email) || isset($this->email_username)) {
            $index = ($this->username_email) ?? ($this->email_username);

            $username = User::where('username', $index)
                ->orWhere('email', $index)->get()
                ->pluck('username')->toArray();

        } elseif (isset($this->username)) {
            $username = User::where('username', $this->username)
                ->get()->pluck('username')->toArray();
        } elseif (isset($this->email)) {
            $username = User::where('email', $this->email)
                ->get()->pluck('username')->toArray();
        } else {
            $username = [];
        }

        $username = ['username' => $username[0] ?? ''];

        $this->merge($username);
    }
}
