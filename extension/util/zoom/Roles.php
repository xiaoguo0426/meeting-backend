<?php


namespace extension\util\zoom;


use GuzzleHttp\Exception\GuzzleException;

class User extends Zoom
{

    public function lists(string $status = 'active', int $page_size = 30, int $role_id = 0, int $page_number = 1, string $next_page_token = '')
    {
        try {
            $query = [
                'status' => $status,
                'page_size' => $page_size,
                'next_page_token' => $next_page_token,
                'page_number' => $page_number,
                'role_id' => $role_id
            ];
            $response = $this->request('/v2/users', $query);
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

    public function assistants(string $user_id)
    {
        try {
            $query = [
                'userId' => $user_id,
            ];
            $response = $this->request('v2/users/' . $user_id . '/assistants', $query);
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