<?php


namespace util\zoom;


use GuzzleHttp\Client;

class AccessToken
{

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function oauth()
    {
        $client = new Client();

        $options = [
            "headers" => [
                "Authorization" => "Basic " . ZoomJwt::genCode()
            ],
        ];

        $response = $client->request('POST', 'https://zoom.us/oauth/token?grant_type=account_credentials&account_id=' . env('ZOOM_ACCOUNT_ID'), $options);
        return json_decode($response->getBody()->getContents(), true);
    }

}