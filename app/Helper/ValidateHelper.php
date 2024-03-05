<?php

namespace App\Helper;

use Illuminate\Support\Facades\Validator;

class ValidateHelper
{
    /**
     * Checks the data against the requests criteria
     * @param array $data data to check as an array
     * @param array $check validator format validation requirements
     * @return object{code: integer, errors: object}
     */
    public static function check(array $data, array $check){
        $validator = Validator::make($data, $check);

        return (object) [
            "code"=> $validator->fails() ? 400 : 200,
            "errors"=> $validator->errors()
        ];
    }
}