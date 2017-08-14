<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamEditRequest extends FormRequest
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
            'org_id' => 'int|min:1',
            'func_id' => 'int|min:1',
            'position_id' => 'int|min:1',
            'user_id' => 'int|min:1',
            'chief_id' => 'int|min:1',
            'count' => 'int|min:1',
            'mark' => 'int|min:0',
//            'note' => 'string',
            'user_sign' => 'string',
            'chief_sign' => 'string',
        ];
    }
}
