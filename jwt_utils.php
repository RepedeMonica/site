<?php
require 'vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

$secret_key = 'cheia_ta_secreta'; 

function validate_jwt() {
    global $secret_key; 

    if (isset($_COOKIE['jwt_token'])) {
        $jwt = $_COOKIE['jwt_token'];

        try {
            $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
            
            if ($decoded->iss !== 'https://MyProject.com') {
                throw new Exception('Invalid issuer');
            }

            return $decoded->data;
            
        } catch (Exception $e) {
            return null;
        }
    }
    return null;
}
?>