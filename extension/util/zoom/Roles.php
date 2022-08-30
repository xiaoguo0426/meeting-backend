<?php


namespace extension\util\zoom;


use GuzzleHttp\Exception\GuzzleException;

class Roles extends Zoom
{

    public function lists()
    {
        try {
            $query = [
            ];
            $response = $this->request('/v2/roles', $query);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            $response = $e->getResponse();
            $body = json_decode($response->getBody(), true);
            if (json_last_error()) {
                $message = $e->getMessage();
                $code = $e->getCode();
            } else {
                $message = $body['message'];
                $code = $body['code'];
            }
            throw new ZoomException($message, $code);
        }
    }
}