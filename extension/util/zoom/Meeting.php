<?php

namespace extension\util\zoom;


use GuzzleHttp\Exception\GuzzleException;

class Meeting extends Zoom
{
    /**
     * @param string $next_page_token
     * @param string $type
     * @param int $page_size
     * @param int $page_number
     * @throws ZoomException
     * @return mixed
     */
    public function lists(string $type = 'live', string $next_page_token = '', int $page_number = 1, int $page_size = 30)
    {
        try {
            $query = [
                'type' => $type,
                'page_size' => $page_size,
                'next_page_token' => $next_page_token,
                'page_number' => $page_number
            ];
            $response = $this->request('/v2/users/me/meetings', $query);
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

    public function user(string $user_id, string $type = 'live', int $page_number = 1, int $page_size = 30, string $next_page_token = '')
    {
        try {
            $query = [
                'type' => $type,
                'page_size' => $page_size,
                'next_page_token' => $next_page_token,
                'page_number' => $page_number
            ];
            $response = $this->request('/v2/users/' . $user_id . '/meetings', $query);
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

    /**
     * 创建会议
     * @param array $data
     * @throws ZoomException
     * @return mixed
     */
    public function create(array $data)
    {
        try {
            $response = $this->request('/v2/users/me/meetings', $data, 'POST');
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

    /**
     * 获取会议
     * @param string $meeting_id
     * @throws ZoomException
     * @return mixed
     */
    public function get(string $meeting_id)
    {
        try {
            $response = $this->request('/v2/meetings/' . $meeting_id, []);
            $meeting = $response->getBody()->getContents();
            return json_decode($meeting, true);
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

    /**
     * 更新会议
     * @param string $meeting_id
     * @param array $data
     * @throws ZoomException
     * @return bool
     */
    public function update(string $meeting_id, array $data): bool
    {
        try {
            return $this->request('/v2/meetings/' . $meeting_id, $data, 'PATCH')->getStatusCode() === 204;
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

    /**
     * 删除会议
     * @param string $meeting_id
     * @throws ZoomException
     * @return bool
     */
    public function delete(string $meeting_id): bool
    {
        try {
            return $this->request('/v2/meetings/' . $meeting_id, [], 'DELETE')->getStatusCode() === 204;
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

    /**
     * 更新会议状态
     * @param string $meeting_id
     * @param string $status
     * @throws ZoomException
     * @return bool
     */
    public function status(string $meeting_id, string $status = ''): bool
    {
        try {
            $data = [
                'action' => $status
            ];
            //只要code为204，即请求成功
            return $this->request('/v2/meetings/' . $meeting_id . '/status', $data, 'POST')->getStatusCode() === 204;
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

    /**
     * @throws ZoomException
     */
    public function inviteLinks(string $meeting_id, array $attendees, int $ttl = 7200)
    {
        try {
            $data = [
                'attendees' => $attendees,
                'ttl' => $ttl
            ];
            //只要code为204，即请求成功
            $response = $this->request('/v2/meetings/' . $meeting_id . '/invite_links', $data, 'POST');
            $links = $response->getBody()->getContents();
            return json_decode($links, true);
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

    /**
     * 批量添加会议注册人(一次最多添加30个)
     * @param string $meeting_id
     * @param array $registrants
     * @throws ZoomException
     * @return mixed
     */
    public function batchRegistrants(string $meeting_id, array $registrants)
    {
        try {
            $data = [
                'auto_approve' => true,
                'registrants' => $registrants
            ];
            $response = $this->request('/v2/meetings/' . $meeting_id . '/batch_registrants', $data, 'POST');
            $reportMeeting = $response->getBody()->getContents();
            return json_decode($reportMeeting, true);
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

    /**
     * 列出所有注册人
     * @param string $meeting_id
     * @param string $status
     * @throws ZoomException
     * @return mixed
     */
    public function listRegistrants(string $meeting_id, string $status = 'approved')
    {
        try {
            //只要code为204，即请求成功
            $response = $this->request('/v2/meetings/' . $meeting_id . '/registrants', [
                'status' => $status
            ]);
            $reportMeeting = $response->getBody()->getContents();
            return json_decode($reportMeeting, true);
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

    /**
     * 添加注册人
     * @param string $meeting_id
     * @param array $registrant
     * @throws ZoomException
     * @return mixed
     */
    public function addRegistrant(string $meeting_id, array $registrant)
    {
        try {
            $response = $this->request('/v2/meetings/' . $meeting_id . '/registrants', $registrant, 'POST');
            $add = $response->getBody()->getContents();
            return json_decode($add, true);
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

    /**
     * 删除注册人
     * @param string $meeting_id
     * @param string $registrant_id
     * @throws ZoomException
     * @return mixed
     */
    public function deleteRegistrants(string $meeting_id, string $registrant_id)
    {
        try {
            $response = $this->request('/v2/meetings/' . $meeting_id . '/registrants/' . $registrant_id, [], 'DELETE');
            $reportMeeting = $response->getBody()->getContents();
            return json_decode($reportMeeting, true);
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