<?php
class HttpClient
{
    private $url;
    private $method;
    private $headers;
    private $payload;

    // Constructor to initialize the URL.
    public function __construct($url)
    {
        $this->url = $url;
        $this->method = 'GET';
        $this->headers = [];
        $this->payload = [];
    }

    // Set the HTTP method.
    public function setMethod($method)
    {
        $this->method = strtoupper($method);
    }

    //Set the HTTP headers.
    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    //Set the JSON payload.
    public function setPayload($payload)
    {
        $this->payload = $payload;
    }

    //Send the HTTP request.
    public function sendRequest()
    {
        $contextOptions = [
            'http' => [
                'method' => $this->method,
                'header' => $this->buildHeaders(),
                'content' => $this->buildPayload(),
                'ignore_errors' => true,
            ],
        ];

        $context = stream_context_create($contextOptions);
        $response = file_get_contents($this->url, false, $context);

        if ($response === false) {
            throw new Exception('Failed to send HTTP request');
        }

        $httpResponseHeader = $this->parseResponseHeaders($http_response_header);
        $statusCode = $httpResponseHeader['status_code'];

        if ($statusCode >= 400) {
            throw new Exception("HTTP request failed with status code $statusCode");
        }

        return [
            'headers' => $httpResponseHeader['headers'],
            'body' => $this->parseResponseBody($response),
        ];
    }

    //Build the headers string.
    private function buildHeaders()
    {
        $headers = [];
        foreach ($this->headers as $key => $value) {
            $headers[] = "$key: $value";
        }
        if ($this->method === 'POST' && !empty($this->payload)) {
            $headers[] = 'Content-Type: application/json';
        }
        return implode("\r\n", $headers);
    }

    // Build the JSON payload.
    private function buildPayload()
    {
        if ($this->method === 'POST' && !empty($this->payload)) {
            $jsonPayload = json_encode($this->payload);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Failed to encode JSON payload');
            }
            return $jsonPayload;
        }
        return null;
    }

    // Parse the response headers.
    private function parseResponseHeaders($headers)
    {
        $headerArray = [];
        $statusCode = null;

        foreach ($headers as $header) {
            if (strpos($header, 'HTTP/') === 0) {
                $statusCode = (int)substr($header, 9, 3);
            } else {
                list($key, $value) = explode(': ', $header, 2);
                $headerArray[$key] = $value;
            }
        }

        return [
            'status_code' => $statusCode,
            'headers' => $headerArray,
        ];
    }

    // Parse the response body.
    private function parseResponseBody($body)
    {
        $decodedBody = json_decode($body, true);
        echo $body;
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Failed to decode JSON response');
        }
        return $decodedBody;
    }
}

?>