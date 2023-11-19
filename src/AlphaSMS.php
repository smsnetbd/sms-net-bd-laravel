<?php

namespace Alphanetbd\SMS;

use Illuminate\Support\Facades\Http;

class AlphaSMS
{
    private $apiUrl = 'https://api.sms.net.bd';
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = env('ALPHANETBD_SMS_API_KEY');
    }

    public function sendSMS($message, $recipients, $senderId = null)
    {
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

    public function sendScheduledSMS( $message, $recipients, $schedule, $senderId = null)
    {
        $url = "{$this->apiUrl}/sendsms";

        $params = [
            'api_key' => $this->apiKey,
            'msg' => $message,
            'to' => $recipients,
            'sender_id' => $senderId,
        ];

        // use dateTime class with Asia/Dhaka timezone and convert it to timestamp
        $scheduleTime = new \DateTime($schedule);

        if (!$scheduleTime) {
            throw new \Exception('Invalid schedule time');
            return;
        }

        $scheduleTime->setTimezone(new \DateTimeZone('Asia/Dhaka'));

        // check the timestamp is before the current time
        if ($scheduleTime->getTimestamp() < time()) {
            throw new \Exception('Scheduled time must be in the future');
            return;
        }

        $params['schedule'] = $scheduleTime->format('Y-m-d H:i:s');

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
            $response = Http::asForm()->acceptJson()->post($url, $params);
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
