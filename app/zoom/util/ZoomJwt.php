<?php

namespace app\zoom\util;

use Firebase\JWT\JWT;

class ZoomJwt
{

    public static function jwt()
    {
        $key = env('ZOOM_API_SECRET');
        $payload = array(
            "iss" => env('ZOOM_API_KEY'),
            'exp' => time() + 3600,
        );
        return JWT::encode($payload, $key, 'HS256');
    }
}