<?php

namespace sms_net_bd;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

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

        $timezone = new \DateTimeZone('Asia/Dhaka');
        $currentDate = new \DateTime(
            'now',
            $timezone
        );

        $scheduleTime = strtotime($schedule, $currentDate->getTimestamp());

        // get the current time in the 'Asia/Dhaka' timezone
        $currentTime = (new \DateTime('now', $timezone))->getTimestamp();

        if ($scheduleTime < $currentTime) {
            throw new \Exception('Schedule time must be in the future');
        }

        $params['schedule'] = date('Y-m-d H:i:s', $scheduleTime); // in YYYY-MM-DD HH:MM:SS format for Asia/Dhaka timezone

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
        $client = new Client();
        
        $options = [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ];
        
        if ($method === 'GET') {
            $options[RequestOptions::QUERY] = $params;
            $response = $client->get($url, $options);
        } else {
            $options[RequestOptions::FORM_PARAMS] = $params;
            $response = $client->post($url, $options);
        }
        
        return json_decode($response->getBody(), true);
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
