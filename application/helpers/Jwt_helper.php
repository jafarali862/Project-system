<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function generate_jwt($data) 
{
    $key = 'YOUR_SECRET_KEY';
    $payload = [
        'iss' => 'your_issuer',
        'aud' => 'your_audience',
        'iat' => time(),
        'exp' => time() + (60*60), // 1 hour
        'data' => $data
    ];
    return JWT::encode($payload, $key, 'HS256');
}

function validate_jwt($token) 
{
    try {
        $decoded = JWT::decode($token, new Key('YOUR_SECRET_KEY', 'HS256'));
        return $decoded->data;
    } catch (Exception $e) {
        return false;
    }
}
