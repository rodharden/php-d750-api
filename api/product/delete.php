<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/product.php';
include_once '../shared/constants.php';
include_once '../shared/validations.php';

$currentStatusCode = Constants::$HTTP_MESSAGE_SUCCESS;
$currentMessage = array("message" => Constants::$CRUD_MESSAGE_DELETE_SUCCESS);
$database = new Database();
$destinationObject = new Product($database->getConnection());
$sourceObject = null;

if (!Validations::isMETHOD('DELETE')) return;
if (!Validations::isBODYNullOrInvalidData()) return;
$data = Validations::$BODYData;
try {
    $allData = get_object_vars($data);
    $allValues = array_values($allData);
    $allKeys = array_keys($allData);
    if (!in_array(Constants::$FIELD_IDENTITY_MAIN, $allKeys)) { 
        throw new Exception(Constants::$FIELD_IDENTITY_MISSING_ERROR); 
    }
    $sourceObject = $destinationObject->readOneWithId($data->id);
    $destinationObject->id = $data->id;
    $rowsAffected = $destinationObject->delete();
    if ($rowsAffected == 0) {
        $currentStatusCode = Constants::$HTTP_MESSAGE_ERROR_INTERNAL_SERVER_ERROR;
        $currentMessage = Constants::$CRUD_MESSAGE_DELETE_ERROR;
    }
} catch (Exception $ex) {
    $currentStatusCode = Constants::$HTTP_MESSAGE_ERROR_UNPROCESSABLE_ENTITY;
    $currentMessage = $ex->getMessage();
}
http_response_code($currentStatusCode);
echo json_encode(array("message" => $currentMessage, "status_code" => $currentStatusCode, "source" => $sourceObject, "destination" => null));