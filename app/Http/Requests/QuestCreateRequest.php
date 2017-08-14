<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestCreateRequest extends FormRequest
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
            'struct' => 'array',
            'task' => 'required_if:word_file,',
            'word_file' => 'required_if:task,|file',
            'timer' => 'int',
        ];
    }
}
