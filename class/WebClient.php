<?php

/*
Created by: Raymond Chong
Date: 2016-10-12
*/

class WebClient {
	public $cookies = [];
	public $xhr;

	public function __construct() {
		$this->xhr = new HttpRequest();
	}

	public function fail(Closure $callback) {
		if ($this->xhr->status != 200) {
			$callback($this->xhr);
		}

		return $this;
	}

	public function done(Closure $callback) {
		if ($this->xhr->status == 200) {
			$contentType = $this->xhr->getResponseHeader("Content-Type");
			if (strstr($contentType, "application/json")) {
				$callback(json_decode($this->xhr->response, true), "", $this->xhr);
			} else {
				$callback($this->xhr->response, "", $this->xhr);
			}
		}
		return $this;
	}

	public static function Post($url, $data, $success, $dataType) {
		$client = is_a($this, __class__) ? $this : new WebClient();
		return $client->Ajax(["type" => "POST", "url" => $url, "data" => $data, "success" => $success, "dataType" => $dataType]);
	}
	// dataType: The type of data expected from the server. Default: Intelligent Guess (xml, json, script, text, html).
	public function Get($url, $data, $success, $dataType) {
		$client = is_a($this, __class__) ? $this : new WebClient();
		return $client->Ajax(["url" => $url, "data" => $data, "success" => $success, "dataType" => $dataType]);
	}

	public static function GetJSON($url, $data, $success) {
		$client = is_a($this, __class__) ? $this : new WebClient();
		return	$client->get($url,$data,$success,"json");
	}

	public function Ajax($url, $settings = []) {
		$client = is_a($this, __class__) ? $this : new WebClient();

		$a = $settings;
		if (!is_array($url)) {
			$a["url"] = $url;
		} else {
			$a = $url;
		}

		if ($a["type"] != "POST") {
			if ($a["data"]) {
				$a["url"] .= "?" . http_build_query($a["data"]);
			}
		}

		$method = $a["type"] ? $a["type"] : "GET";
		$client->xhr->open($method, $a["url"]);
		if ($a["dataType"] == "json") {
			$client->xhr->setRequestHeader("Accept", "application/json");
		}

		if ($a["contentType"]) {
			$client->xhr->setRequestHeader("content-type", $a["contentType"]);
		}
		
		if($client->cookies){
			$client->xhr->setRequestHeader("Cookie",implode("; ",$client->cookies));
		}

		if (is_array($a["headers"])) {
			foreach ($a["headers"] as $name => $value) {
				$client->xhr->setRequestHeader($name, $value);
			}
		}

		if ($a["type"] == "POST") {
			if ($a["contentType"] == "application/json") {
				$client->xhr->send(json_encode($a["data"]));
			} else {
				$client->xhr->send(http_build_query($a["data"]));
			}
		} else {
			$client->xhr->send();
		}

		if ($cookies = $client->xhr->getResponseHeader("Set-Cookie")) {
			foreach (explode(";", $cookies) as $cookie) {
				$cookie = ltrim($cookie);
				$cookie_name = explode("=", $cookie, 2)[0];
				if ($cookie_name == "path")
					continue;
				$client->cookies[] = $cookie;
			}
		}

		if (strstr($client->xhr->getResponseHeader("Content-Type"), "application/json")) {
			$client->responseJSON = json_decode($client->xhr->response, true);
		}

		if ($a["success"] && $a["success"] instanceof Closure) {
			$client->done($a["success"]);
		}

		return $client;
	}
}
