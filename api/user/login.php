<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once './authenticate.php';
require "../../vendor/autoload.php";
use \Firebase\JWT\JWT;

$email = '';
$password = '';
$databaseService = new Database();
$conn = $databaseService->getConnection();
$data = json_decode(file_get_contents("php://input"));
$secondsNotBefore = 0;
$secondsToExpire = 6000;

$email = $data->email;
$password = $data->password;
$table_name = 'users';
$query = "SELECT id, first_name, last_name, password FROM " . $table_name . " WHERE email = ? LIMIT 0,1";

$stmt = $conn->prepare( $query );
$stmt->bindParam(1, $email);
$stmt->execute();
$num = $stmt->rowCount();

if($num > 0){
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $id = $row['id'];
    $firstname = $row['first_name'];
    $lastname = $row['last_name'];
    $password2 = $row['password'];

    if(password_verify($password, $password2))
    {
        $secret_key = AuthConstants::$secret_key;
        $issuer_claim = AuthConstants::$issuer_claim; // this can be the servername
        $audience_claim = AuthConstants::$audience_claim;
        $issuedat_claim = time(); // issued at
        $notbefore_claim = $issuedat_claim + $secondsNotBefore; //not before in seconds
        $expire_claim = $issuedat_claim + $secondsToExpire; // expire time in seconds
        $token = array(
            "iss" => $issuer_claim,
            "aud" => $audience_claim,
            "iat" => $issuedat_claim,
            "nbf" => $notbefore_claim,
            "exp" => $expire_claim,
            "data" => array(
                "id" => $id,
                "firstname" => $firstname,
                "lastname" => $lastname,
                "email" => $email
        ));
        http_response_code(200);
        $jwt = JWT::encode($token, $secret_key, 'HS256');
        echo json_encode(
            array(
                "message" => "Successful login.",
                "jwt" => $jwt,
                "email" => $email,
                "expireAt" => $expire_claim,
                "expireData" => date_format(date_timestamp_set(date_create(), $expire_claim), Constants::$DATE_FORMAT_DATE_US)
            ));
    }
    else{
        http_response_code(401);
        echo json_encode(array("message" => "Login failed.", "password" => $password));
    }
}
?>