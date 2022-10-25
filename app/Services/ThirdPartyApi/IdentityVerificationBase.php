<?php

namespace App\Services\ThirdPartyApi;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class IdentityVerificationBase
{
    /**
     * @var Repository|Application|mixed
     */
    protected $baseUrl;
    /**
     * @var Repository|Application|mixed
     */
    protected $clientId;
    /**
     * @var Repository|Application|mixed
     */
    protected $secretKey;

    public function __construct()
    {
        $this->baseUrl = config('topupspace.inter_switch_base_url');
        $this->clientId = config('topupspace.client_id');
        $this->secretKey =config('topupspace.secret_key');

    }

    /**
     * @return mixed|null
     */
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

    /**
     * @param $path
     * @param $data
     * @return JsonResponse|null
     */
    public function makePostRequest($path, $data):?JsonResponse
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
