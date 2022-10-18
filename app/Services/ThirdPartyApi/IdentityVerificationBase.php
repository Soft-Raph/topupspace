<?php

namespace App\Services\ThirdPartyApi;

use Illuminate\Support\Facades\Http;

class IdentityVerificationBase
{
    protected $baseUrl;
    protected $clientId;
    protected $secretKey;
    public function __construct()
    {
        $this->baseUrl = config('topupspace.inter_switch_base_url');
        $this->clientId = config('topupspace.client_id');
        $this->secretKey =config('topupspace.secret_key');

    }

    public function getToken()
    {
        $path ="passport/oauth/token?grant_type=client_credentials";
        $endpoint = "{$this->baseUrl}/{$path}";
        $code = "$this->clientId:$this->secretKey";
        $baseEncode = base64_encode($code);
        $response = Http::withHeaders(
            [
                'Accept' => 'application/json',
                "Authorization" =>"Basic ".$baseEncode,
                'Content-Type' => 'application/json'
            ]
        )->post($endpoint)->json();
        if($response && isset($response['access_token'])) {
            return $response['access_token'];
        }
        return null;
    }
    public function makePostRequest($path, $data)
    {
        $endpoint = "{$this->baseUrl}/{$path}";
        return Http::withHeaders(
            [
                'Accept' => 'application/json',
                "Authorization" =>"Bearer ".$this->getToken(),
                'Content-Type' => 'application/json'
            ]
        )->post($endpoint, $data)->json();
    }
}
