<?php

namespace Alphanetbd\Alphasms;

class AlphaSMS
{
    // api url and api key as properties
    private $apiUrl = 'https://api.sms.net.bd';
    private $apiKey;

    // constructor, get apikey from default env file
    public function __construct () {
        $this->apiKey = env('ALPHANETBD_SMS_API_KEY');
    }

    public function sendSMS($message, $recipients, $schedule = null, $senderId = null)
    {
        $url = $this->apiUrl . '/sendsms';
        $params = [
            'api_key' => $this->apiKey,
            'msg' => $message,
            'to' => $recipients
        ];

        if ($schedule) {
            // Convert $schedule to a MySQL datetime format
            $scheduleTimestamp = strtotime($schedule);
            $params['schedule'] = date('Y-m-d H:i:s', $scheduleTimestamp);
        }

        if ($senderId) {
            $params['sender_id'] = $senderId;
        }

        $response = $this->makeRequest($url, 'POST', $params);
        return $response;
    }

    public function getReport($requestId)
    {
        $url = $this->apiUrl . '/report/request/' . $requestId;
        $params = ['api_key' => $this->apiKey];
        $response = $this->makeRequest($url, 'GET', $params);
        return $response;
    }

    public function getBalance()
    {
        $url = $this->apiUrl . '/user/balance';
        $params = ['api_key' => $this->apiKey];
        $response = $this->makeRequest($url, 'GET', $params);
        return $response;
    }

    private function makeRequest($url, $method, $params)
    {
        $ch = curl_init();

        if ($method === 'GET') {
            $url .= '?' . http_build_query($params);
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}
