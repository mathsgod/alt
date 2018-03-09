<?php
/*
	Created by: Raymond Chong
	Date: 2016-10-12
*/

class HttpRequest {
    public $curl;
    public $response;
    public $statusText;
    public $status;
    public $responseHeader;

    public function __construct() {
        $this->curl = curl_init();
    }

    public function open($method, $url) {
        curl_setopt($this->curl, CURLOPT_URL, $url);
        if ($method == "POST") {
            curl_setopt($this->curl, CURLOPT_POST, 1);
        }
    }

    public function send($params) {
        curl_setopt($this->curl, CURLOPT_HEADER, 1);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_SSLVERSION, 6);

        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->requestHeader);

        if ($params) {
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $params);
        }

        $response = curl_exec($this->curl);
        $info = curl_getinfo($this->curl);

        $this->responseHeader = substr($response, 0, $info["header_size"]);
        $this->responseText = substr($response, $info["header_size"]);

        $this->response = $this->responseText;

        $this->status = $info["http_code"];
        curl_close($this->curl);
    }

    private $requestHeader = [];
    public function setRequestHeader($header, $value) {
        $this->requestHeader[] = "$header: $value";
    }

    public function getResponseHeader($name) {
        $hs = [];
        foreach(explode("\n", $this->responseHeader) as $line) {
            list($a, $b) = explode(":", $line, 2);
            $hs[$a] = trim($b);
        }

        return $hs[$name];
    }
}