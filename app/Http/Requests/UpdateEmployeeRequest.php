<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'email', 'unique:users,email'],
            'first_name' => ['required'],
            'last_name' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'job_title' => ['required'],
            'location' => ['required'],
        ];
    }
}
