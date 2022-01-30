<?php
require "../../vendor/autoload.php";

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;


class Authenticate
{
    public $keyOrException = null;
    public function __construct()
    {
        try {
            $authHeader = explode(" ", $_SERVER['HTTP_AUTHORIZATION']);
            $jwt = $authHeader[1];
            if ($jwt) {
                $this->keyOrException = JWT::decode($jwt, new Key(AuthConstants::$secret_key, 'HS256'));
            }
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(array("message" => "Access denied.", "error" => $e->getMessage()));
            $this->keyOrException =  $e;
        }
    }

    

}

class AuthConstants
{
    public static $secret_key = "drink_secret_2022";
    public static $issuer_claim = "drink750.ml";
    public static $audience_claim = "ordinary_users";
}
