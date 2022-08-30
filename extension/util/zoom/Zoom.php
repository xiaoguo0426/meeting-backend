<?php

namespace extension\util\zoom;

use GuzzleHttp\Client;

abstract class Zoom
{
    /**
     * @param $uri
     * @param $data
     * @param string $method
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function request($uri, $data, string $method = 'GET'): \Psr\Http\Message\ResponseInterface
    {
        $client = new Client([
            'base_uri' => 'https://api.zoom.us',
        ]);
        $options = [
            "headers" => [
                "Authorization" => "Bearer " . ZoomJwt::jwt()
            ],
        ];
        if ($method === 'GET') {
            $options['query'] = $data;
        } else {
            $options['json'] = $data;
        }
        return $client->request($method, $uri, $options);
    }

}