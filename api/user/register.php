<?php
header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../shared/constants.php';
include_once '../shared/validations.php';

$currentStatusCode = Constants::$HTTP_MESSAGE_SUCCESS;
$currentMessage = array("message" => Constants::$CRUD_MESSAGE_UPDATE_SUCCESS);
$database = new Database();
$conn = $database->getConnection();
$allowedFields = array("first_name", "last_name", "email", "password");

if (!Validations::isMETHOD('POST')) return;
if (!Validations::isBODYNullOrInvalidData()) return;
$data = Validations::$BODYData;
if (!Validations::isValidEntity($allowedFields, $data)) return;
$sourceObject = array("first_name" => $data->first_name, "last_name" => $data->last_name, "email" => $data->email, "password" => $data->password);

$firstName = $data->first_name;
$lastName = $data->last_name;
$email = $data->email;
$password = $data->password;
$table_name = 'users';
$query = "INSERT INTO " . $table_name . "
                SET first_name = :firstname,
                    last_name = :lastname,
                    email = :email,
                    password = :password";

$stmt = $conn->prepare($query);
$stmt->bindParam(':firstname', $firstName);
$stmt->bindParam(':lastname', $lastName);
$stmt->bindParam(':email', $email);
$password_hash = password_hash($password, PASSWORD_BCRYPT);
$stmt->bindParam(':password', $password_hash);
$destinationObject = $sourceObject;
$destinationObject["created"] = date_format(date_timestamp_set(date_create(), time()), Constants::$DATE_FORMAT_DATE_US);
$destinationObject["modified"] = date_format(date_timestamp_set(date_create(), time()), Constants::$DATE_FORMAT_DATE_US);
$rowsAffected = $stmt->execute();
if ($rowsAffected == 0) {
    $currentStatusCode = Constants::$HTTP_MESSAGE_ERROR_UNPROCESSABLE_ENTITY;
    $currentMessage = Constants::$MESSAGE_USER_REGISTERUSER_ERROR;
}
http_response_code($currentStatusCode);
echo json_encode(array("message" => $currentMessage, "status_code" => $currentStatusCode, "source" => $sourceObject, "destination" => $destinationObject));