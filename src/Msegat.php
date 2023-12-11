<?php

namespace BitcodeSa\Msegat;

use Illuminate\Support\Facades\Http;

class Msegat
{
    protected $api_url;
    protected $username;
    protected $sender;
    protected $api_key;
    protected $unicode;
    protected $request;
    protected $client;
    protected $numbers;
    protected $message;
    protected $response;

    public function __construct($username = null, $sender = null, $unicode = null)
    {
        $this->setApiUrl();
        $this->setAuthentication();
        $this->setUsername($username);
        $this->setSender($sender);
        $this->setUnicode($unicode);
        $this->setClient();
    }

    public function setApiUrl()
    {
        $this->api_url = config("msegat.api_url");
        return $this;
    }

    public function setAuthentication()
    {
        $this->api_key = config("msegat.api_key");
        return $this;
    }

    public function setUsername($username = null)
    {
        $this->username = $username ?? config("msegat.username");
        return $this;
    }

    public function setSender($sender = null)
    {
        $this->sender = $sender ?? config("msegat.sender");
        return $this;
    }

    public function setUnicode($unicode = null)
    {
        $this->unicode = $unicode ?? config("msegat.unicode");
    }

    public function setClient()
    {
        $this->client = Http::withHeaders([
            "Content-Type" => " application/json",
        ])->acceptJson()->baseUrl($this->api_url);
    }

    public function setRequest()
    {
        $this->request = [
            "userName" => $this->username,
            "userSender" => $this->sender,
            "apiKey" => $this->password,
            "msgEncoding" => $this->unicode,
            "numbers" => $this->numbers,
            "msg" => $this->message,
        ];
    }

    public function setNumbers($numbers)
    {
        if (is_array($numbers)) {
            $numbers = implode(",", $numbers);
        }

        $this->numbers = $numbers;

        return $this;
    }

    public function setMessage($message)
    {
        $this->message = str_replace('<br>', ' ', $message);
        return $this;
    }

    public function sendMessage($numbers, $message, $sender = null)
    {
        $this->setNumbers($numbers);
        $this->setMessage($message);
        $this->setSender($sender);
        return $this->sendRequest();
    }

    public function sendRequest()
    {
        $this->setRequest();

        $this->response = $this->client->post("", $this->request);

        return $this->response;
    }
}
