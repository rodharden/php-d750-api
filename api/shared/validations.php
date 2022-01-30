<?php

class Validations
{
    public static function isMETHOD($methodname)
    {
        $allowedRequestMethods = array($methodname, 'HEAD');
        $currentRequestMethod = $_SERVER['REQUEST_METHOD'];
        if (!in_array($currentRequestMethod, $allowedRequestMethods)) {
            http_response_code(Constants::$HTTP_MESSAGE_ERROR_METHOD_NOT_ALLOWED);
            echo json_encode(array("message" => "Method Not Allowed: " . $_SERVER['REQUEST_METHOD'], "error_code" => Constants::$HTTP_MESSAGE_ERROR_METHOD_NOT_ALLOWED));
            return false;
        }
        return true;
    }

    public static $BODYData = null;
    public static function isBODYNullOrInvalidData()
    {
        $data = json_decode(file_get_contents("php://input"));
        if ($data == null) {
            http_response_code(Constants::$HTTP_MESSAGE_ERROR_UNPROCESSABLE_ENTITY);
            echo json_encode(array("message" => "The body format is invalid", "error_code" => Constants::$HTTP_MESSAGE_ERROR_UNPROCESSABLE_ENTITY));
            return false;
        }
        Validations::$BODYData = $data;
        return true;
    }

    public static function isValidEntity($arrayFields, $objectToCompare) {
        $allKeys = array_values($arrayFields);
        $error = true;
        foreach($allKeys as $key) {
             if (!property_exists($objectToCompare, $key)) {
                 $error = false;
                 break;
             }
        }
        if (!$error) {
            http_response_code(Constants::$HTTP_MESSAGE_ERROR_UNPROCESSABLE_ENTITY);
            echo json_encode(array("message" => "The body format is invalid", "error_code" => Constants::$HTTP_MESSAGE_ERROR_UNPROCESSABLE_ENTITY));
        }
        return $error;
    }
}
