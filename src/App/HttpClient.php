<?php

namespace App;

/**
 * Class HttpClient
 * @package App
 */
class HttpClient
{
    /**
     * Setup a cURL request and return the response
     *
     * @param string $path
     * @param string $method
     * @param array $headers
     * @param array $basicAuth
     * @param array $body
     * @param bool $isJson
     * @param int $connectionTimeout
     * @param int $cURLTimeout
     * @param bool $failOn4xx
     * @param bool $verifySSL
     * @param array $customOptions
     * @return array
     */
    public static function cURL(
        string $path,
        string $method = 'GET',
        array $headers = [],
        array $basicAuth = [
            'username' => null,
            'password' => null,
        ],
        array $body = [],
        bool $isJson = false,
        int $connectionTimeout = 10,
        int $cURLTimeout = 60,
        bool $failOn4xx = false,
        bool $verifySSL = true,
        array $customOptions = []
    ): array
    {
        // Initializes a new session and returns a cURL handle
        $curl = curl_init();

        if (!$curl) {
            die("Couldn't initialize a cURL handle!");
        }

        // Set options for the cURL transfer
        $isBody = count($body) == 0;
        if ($isJson) {
            $headers = array_merge(['Content-Type: application/json'], $headers);
        }
        $isBasicAuth = !is_null($basicAuth['username']) || !is_null($basicAuth['password']);
        $options = [
            CURLOPT_URL => $path,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HEADER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_USERPWD => $isBasicAuth ? implode(':', array_values($basicAuth)) : '',
            CURLOPT_NOBODY => $isBody,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $method != 'POST' ? http_build_query($body) : ($isJson ? json_encode($body) : $body),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => $connectionTimeout,
            CURLOPT_TIMEOUT => $cURLTimeout,
            CURLOPT_FAILONERROR => $failOn4xx,
            CURLOPT_SSL_VERIFYHOST => $verifySSL,
            CURLOPT_SSL_VERIFYPEER => $verifySSL,
        ];
        curl_setopt_array($curl, array_merge($options, $customOptions));

        // Executes the given cURL session
        $response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);

        // Check the errors
        if (curl_error($curl)) {
            Helper::log('cURL error: ' . curl_error($curl));
        }

        // Closes the cURL session
        curl_close($curl);

        return [
            'status' => $status,
            'response' => $response,
        ];
    }
}
