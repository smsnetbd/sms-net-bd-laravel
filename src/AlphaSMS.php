<?php

namespace Alphanetbd\Alphasms;

use Illuminate\Support\Facades\Http;

class AlphaSMS
{
    private $apiUrl = 'https://api.sms.net.bd';
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = env('ALPHANETBD_SMS_API_KEY');
    }

    public function sendSMS($message, $recipients, $senderId = null, $schedule = null)
    {
        $url = "{$this->apiUrl}/sendsms";

        $params = [
            'api_key' => $this->apiKey,
            'msg' => $message,
            'to' => $recipients,
            'sender_id' => $senderId,
        ];

        if ($schedule) {
            // Convert $schedule to a MySQL datetime format
            $scheduleTimestamp = strtotime($schedule);
            $params['schedule'] = date('Y-m-d H:i:s', $scheduleTimestamp);
        }

        $response = $this->makeRequest('POST', $url, $params);

        return $this->handleResponse($response);
    }


    public function getReport($requestId)
    {
        $url = "{$this->apiUrl}/report/request/{$requestId}";

        $params = ['api_key' => $this->apiKey];

        $response = $this->makeRequest('GET', $url, $params);

        return $this->handleResponse($response);
    }

    public function getBalance()
    {
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
            $response = Http::acceptJson()->post($url, $params);
        }

        return $response->json();
    }

    private function handleResponse($response)
    {
        if (isset($response['error']) && $response['error'] == 0) {
            return $response['data'] ?? null;
        }

        // Log or handle the error as needed
        // For now, let's throw an exception with the error message
        throw new \Exception($response['msg'] ?? 'Unknown error');

        return false;
    }
}
