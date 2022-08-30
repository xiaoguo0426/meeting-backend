<?php


namespace app\zoom\util;


use app\zoom\exception\ZoomException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Zoom
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function request($uri, $data, $method = 'GET')
    {
        $client = new Client([
            'base_uri' => 'https://api.zoom.us',
        ]);

        return $client->request($method, $uri, [
            "headers" => [
                "Authorization" => "Bearer " . ZoomJwt::jwt()
            ],
            'json' => $data,
        ]);
    }

    /**
     * @throws ZoomException
     */
    public function createMeeting($data)
    {
        try {
            $response = $this->request('/v2/users/me/meetings', $data, 'POST');
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            throw new ZoomException($e->getMessage());
        }
    }

    public function updateMeeting($meeting_id, $data)
    {
        try {
            //只要code为204，即请求成功
            return $this->request('/v2/meetings/' . $meeting_id, $data, 'PATCH')->getStatusCode() === 204;
        } catch (GuzzleException $e) {
            throw new ZoomException($e->getMessage());
        }
    }

    public function reportMeeting($meeting_id)
    {
        try {
            //只要code为204，即请求成功
            $response = $this->request('/v2/report/meetings/' . $meeting_id, [], 'GET');
            $reportMeeting = $response->getBody()->getContents();
            return json_decode($reportMeeting, true);
        } catch (GuzzleException $e) {
            throw new ZoomException($e->getMessage());
        }
    }
}