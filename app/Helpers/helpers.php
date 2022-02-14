<?php

namespace App\Helpers;

class Helpers
{
    function GerarGuid()
    {
        if (function_exists('com_create_guid')){
            return trim(com_create_guid(), '{}');
        }
        else {
            mt_srand((double)microtime()*10000);
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);
            $uuid = substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12);
            return $uuid;
        }
    }
    
    function arrayToObject($array) {
        if (!is_array($array)) {
            return $array;
        }
                
        $object =  new \stdClass;
        if (is_array($array) && count($array) > 0) {
            foreach ($array as $name=>$value) {
                $name = strtolower(trim($name));
                if (!empty($name)) {
                    $object->$name = $this->arrayToObject($value);
                }
            }
            return $object;
        }
        else {
            return FALSE;
        }
    }

    function requestBodyToObject($request) {
        $requestArray = $request->all();

        if (!is_array($requestArray)) {
            return $request;
        }
                
        $object =  new \stdClass;
        if (is_array($requestArray) && count($requestArray) > 0) {
            foreach ($requestArray as $name=>$value) {
                $name = strtolower(trim($name));
                if (!empty($name)) {
                    $object->$name = $this->arrayToObject($value);
                }
            }
            return $object;
        }
        else {
            return FALSE;
        }
    }

    function generateReponse($httpStatus, $message, $data){
        $response = array(
            "status" => $httpStatus,
            "message" => $message,
            "data" => $data
        );
        return $response;
    }
}