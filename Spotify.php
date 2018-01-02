<?php
class Spotify {
	public function __construct($clientId, $clientSecret) {
		$this->clientId = $clientId;
		$this->clientSecret = $clientSecret;
		$this->base = "https://api.spotify.com/v1/";
	}

	public function getToken() {
		return $this->token;
	}

	public function setToken($token) {
		$this->token = $token;
	}

	public function auth() {
		if(isset($this->token))
			return;

		$ch = curl_init("https://accounts.spotify.com/api/token?grant_type=client_credentials");

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, 0);

		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Authorization: Basic " . base64_encode($this->clientId . ":" . $this->clientSecret)
		));

		$json = curl_exec($ch);
		curl_close($ch);

		$data = json_decode($json);

		$this->setToken($data->access_token);
	}

	public function get($endpoint) {
		$ch = curl_init($this->base . $endpoint . "?access_token=" . $this->token);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, 0);

		$json = curl_exec($ch);
		curl_close($ch);

		return json_decode($json);
	}
}
