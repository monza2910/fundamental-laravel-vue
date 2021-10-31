<?php

namespace App\Services;

class ResponseService{
    
    public function successResponse($result, $message){
        $response       = [
            'success'   => true,
            'data'      => $result,
            'message'   => $message
        ];

        return response()->json($response, 200);;
    }

    public function errorResponse($error, $errorMessages = [], $errorCode = 404){
        $response       = [
            'success'   => true,
            'message'   => $error
        ];

        if (!empty($errorMessages)) {
            $response['data']   = $errorMessages;
        }
    
        return response()->json($response, $errorCode);;
    }
}