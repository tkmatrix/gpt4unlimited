<?php

namespace App\Helper;

use Illuminate\Support\Facades\Validator;

class ValidateHelper
{
    /**
     * Checks the data against the requests criteria
     * @param array $data data to check as an array
     * @param array $check validator format validation requirements
     * @return object{code: integer, response: JsonResponse}
     */
    public static function check(array $data, array $check){
        $validator = Validator::make($data, $check);

        if($validator->fails()){
            $response = response()->json([ "errors"=> $validator->errors() ], 400);
            return (object) ["code"=> 400, "response"=> $response];
        }

        return (object) [ "code"=> 200 ];
    }
}