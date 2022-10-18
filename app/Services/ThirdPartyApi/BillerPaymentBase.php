<?php

namespace App\Services\ThirdPartyApi;

use Illuminate\Support\Facades\Http;

class BillerPaymentBase
{

    protected $baseUrl;
    protected $clientId;
    protected $secretKey;
    protected $terminalId;
    public function __construct()
    {
        $this->baseUrl = config('topupspace.inter_switch_base_url');
        $this->clientId = config('topupspace.client_id');
        $this->secretKey =config('topupspace.secret_key');
        $this->terminalId =config('topupspace.terminal_id');

    }
    public function makeGetRequestAirtime($path)
    {
        $endpoint = "{$this->baseUrl}/{$path}";
        $code = "$this->clientId:$this->secretKey";
        $baseEncode = base64_encode($code);
        return Http::withHeaders(
             [
                 'Accept' => 'application/json',
                 "Authorization" =>"InterswitchAuth ".$baseEncode,
                 'Content-Type' => 'application/json',
                 "TerminalID"=>"3DMO0001",
                 "SignatureMethod"=>"SHA1",
                 "Nonce"=>"ertyu",
                 "Signature"=>'ertyui',
                 "Timestamp"=>'ertyu'
             ]
         )->get($endpoint)->json();
    }

    public function makePostRequestAirtime($path, $data)
    {
        $endpoint = "{$this->baseUrl}/{$path}";
        $code = "$this->clientId:$this->secretKey";
        $baseEncode = base64_encode($code);
        return Http::withHeaders(
             [
                 'Accept' => 'application/json',
                 "Authorization" =>"InterswitchAuth ".$baseEncode,
                 'Content-Type' => 'application/json',
                 "TerminalID"=>$this->terminalId,
                 "SignatureMethod"=>"SHA1",
                 "Nonce"=>"ertyu",
                 "Signature"=>'ertyui',
                 "Timestamp"=>'ertyu'
             ]
         )->post($endpoint, $data)->json();
    }

}
