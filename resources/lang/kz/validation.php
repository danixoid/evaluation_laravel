<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Поле following language lines contain the default errили messages used by
    | the validatили class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'Поле :attribute должно быть притяно.',
    'active_url'           => 'Поле :attribute не валидная URL.',
    'after'                => 'Поле :attribute должно быть датой позже :date.',
    'after_or_equal'       => 'Поле :attribute должно быть датой позже или эквивалентно to :date.',
    'alpha'                => 'Поле :attribute может содержать только буквы.',
    'alpha_dash'           => 'Поле :attribute может содержать только буквы, цифры, и дефисы.',
    'alpha_num'            => 'Поле :attribute может содержать только буквы и цифры.',
    'array'                => 'Поле :attribute должно быть массивом.',
    'before'               => 'Поле :attribute должно быть датой позже, чем :date.',
    'before_or_equal'      => 'Поле :attribute должно быть датой позже или эквивалентно :date.',
    'between'              => [
        'numeric' => 'Поле :attribute должно быть между :min и :max.',
        'file'    => 'Поле :attribute должно быть между :min и :max килобайтов.',
        'string'  => 'Поле :attribute должно быть между :min и :max символов.',
        'array'   => 'Поле :attribute must have между :min и :max элементов.',
    ],
    'boolean'              => 'Поле :attribute должно быть true или false.',
    'confirmed'            => 'Поле :attribute подтверждению не соответствует.',
    'date'                 => 'Поле :attribute не валидная дата.',
    'date_format'          => 'Поле :attribute не соответствует формату :format.',
    'different'            => 'Поля :attribute и :other должно быть разными.',
    'digits'               => 'Поле :attribute должно быть :digits числами.',
    'digits_between'       => 'Поле :attribute должно быть между :min и :max числами.',
    'dimensions'           => 'Поле :attribute имеет не валидные размеры изображения.',
    'distinct'             => 'Поле :attribute имеет дублированное значение.',
    'email'                => 'Поле :attribute должно быть валидным email адресом.',
    'exists'               => 'Выбранное поле :attribute не валидное.',
    'file'                 => 'Поле :attribute должно быть file.',
    'filled'               => 'Поле :attribute должно иметь значение.',
    'image'                => 'Поле :attribute должно быть image.',
    'in'                   => 'Выбранное поле :attribute не валидное.',
    'in_array'             => 'Поле :attribute не существует :other.',
    'integer'              => 'Поле :attribute должно быть integer.',
    'ip'                   => 'Поле :attribute должно быть валидным IP адресом.',
    'ipv4'                 => 'Поле :attribute должно быть валидным IPv4 адресом.',
    'ipv6'                 => 'Поле :attribute должно быть валидным IPv6 адресом.',
    'json'                 => 'Поле :attribute должно быть валидным JSON строкой.',
    'max'                  => [
        'numeric' => 'Поле :attribute не может быть больше, чем :max.',
        'file'    => 'Поле :attribute не может быть больше, чем :max килобайтов.',
        'string'  => 'Поле :attribute не может быть больше, чем :max символов.',
        'array'   => 'Поле :attribute не может иметь больше, чем :max элементов.',
    ],
    'mimes'                => 'Поле :attribute должно быть с типом файла: :values.',
    'mimetypes'            => 'Поле :attribute должно быть с типом файла: :values.',
    'min'                  => [
        'numeric' => 'Поле :attribute должно быть хотя бы :min.',
        'file'    => 'Поле :attribute должно быть хотя бы :min килобайтов.',
        'string'  => 'Поле :attribute должно быть хотя бы :min символов.',
        'array'   => 'Поле :attribute должен иметь хотя бы :min элементов.',
    ],
    'not_in'               => 'Выбранное поле :attribute не валидное.',
    'numeric'              => 'Поле :attribute должно быть числовым.',
    'present'              => 'Поле :attribute должно быть представлено.',
    'regex'                => 'Поле :attribute format не валидное.',
    'required'             => 'Поле :attribute не найдено.',
    'required_if'          => 'Поле :attribute не найдено, когда :other равен ":value".',
    'required_unless'      => 'Поле :attribute не найдено unless :other в :values.',
    'required_with'        => 'Поле :attribute не найдено, когда :values представлен.',
    'required_with_all'    => 'Поле :attribute не найдено, когда :values представлен.',
    'required_without'     => 'Поле :attribute не найдено, когда :values не представлен.',
    'required_without_all' => 'Поле :attribute не найдено, когда ничего из :values не представлено.',
    'same'                 => 'Поле :attribute и :other должны соответствовать.',
    'size'                 => [
        'numeric' => 'Поле :attribute должно быть :size.',
        'file'    => 'Поле :attribute должно быть :size килобайтов.',
        'string'  => 'Поле :attribute должно быть :size символов.',
        'array'   => 'Поле :attribute must contain :size элементов.',
    ],
    'string'               => 'Поле :attribute должно быть строкой.',
    'timezone'             => 'Поле :attribute должно быть валидным zone.',
    'unique'               => 'Поле :attribute уже использовано.',
    'uploaded'             => 'Поле :attribute не загружены на сервер.',
    'url'                  => 'Поле :attribute format не валидное.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages fили attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify specific custom language line fили given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | Поле following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages little cleaner.
    |
    */

    'attributes' => [],

];
