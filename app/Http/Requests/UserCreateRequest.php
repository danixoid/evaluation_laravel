<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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
            'file' => 'required_without:org_id,position_id,user_id,eval_type_id|file',
            'name' => 'required_without:file|string|max:255',
//            'iin' => 'regex:/\d{12}/',
            'email' => 'required_without:file|string|max:255|unique:users',
            'password' => 'required_without:file|string|min:6|confirmed',
        ];
    }
}
