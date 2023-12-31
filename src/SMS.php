<?php

namespace sms_net_bd;

use Illuminate\Support\Facades\Http;

class SMS
{
    private $apiUrl = 'https://api.sms.net.bd';
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = env('SMS_NET_BD_API_KEY');
    }

    public function sendSMS($message, $recipients, $senderId = null)
    {
        // check api key
        if (empty($this->apiKey)) {
            throw new \Exception('API key is required in .env file');
        }

        $url = "{$this->apiUrl}/sendsms";

        $params = [
            'api_key' => $this->apiKey,
            'msg' => $message,
            'to' => $recipients,
            'sender_id' => $senderId,
        ];

        $response = $this->makeRequest('POST', $url, $params);

        return $this->handleResponse($response);
    }

    public function sendScheduledSMS($message, $recipients, $schedule, $senderId = null)
    {
        // check api key
        if (empty($this->apiKey)) {
            throw new \Exception('API key is required in .env file');
        }

        $url = "{$this->apiUrl}/sendsms";

        $params = [
            'api_key' => $this->apiKey,
            'msg' => $message,
            'to' => $recipients,
            'sender_id' => $senderId,
        ];

        $currentDate = new \DateTime('now', new \DateTimeZone('Asia/Dhaka'));

        $scheduleTime = strtotime(
            $schedule,
            $currentDate->getTimestamp()
        );

        // check the timestamp is before the current time
        if ($scheduleTime < time()) {
            throw new \Exception('Schedule time must be in the future');
        }

        $params['schedule'] = date('Y-m-d H:i:s', $scheduleTime); // in YYYY-MM-DD HH:MM:SS format

        $response = $this->makeRequest('POST', $url, $params);

        return $this->handleResponse($response);
    }

    public function getReport($requestId)
    {
        // check api key
        if (empty($this->apiKey)) {
            throw new \Exception('API key is required in .env file');
        }

        $url = "{$this->apiUrl}/report/request/{$requestId}";

        $params = ['api_key' => $this->apiKey];

        $response = $this->makeRequest('GET', $url, $params);

        return $this->handleResponse($response);
    }

    public function getBalance()
    {
        // check api key
        if (empty($this->apiKey)) {
            throw new \Exception('API key is required in .env file');
        }

        $url = "{$this->apiUrl}/user/balance";

        $params = ['api_key' => $this->apiKey];

        $response = $this->makeRequest('GET', $url, $params);

        return $this->handleResponse($response);
    }

    private function makeRequest($method, $url, $params)
    {
        if ($method === 'GET') {
            $response = Http::acceptJson()->get($url, $params);
        } else {
            $response = Http::asForm()->acceptJson()->post($url, $params);
        }

        return $response->json();
    }

    private function handleResponse($response)
    {
        if (isset($response['error']) && $response['error'] == 0) {
            return $response['data'] ?? $response['msg'];
        }

        // Log or handle the error as needed
        // For now, let's throw an exception with the error message
        throw new \Exception($response['msg'] ?? 'Unknown error');
    }
}
