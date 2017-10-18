<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompetenceCreateRequest extends FormRequest
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
            'word_file' => 'required_without:name,note|file',
            'name' => 'required_without:word_file|string',
            'note' => 'required_without:word_file|string',
            'indicator' => 'required_without:word_file|array',
            'org_id' => 'int|min:1',
//            'func_id' => 'int|min:1',
            'position_id' => 'int|min:1',
        ];
    }
}
