<?php

namespace extension\util\zoom;

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

    public static function genCode()
    {
        return base64_encode(env('ZOOM_API_KEY') . ':' . env('ZOOM_API_SECRET'));
    }
}