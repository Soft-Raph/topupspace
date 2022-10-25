<?php

namespace App\Http\Controllers;

use App\Exceptions\SwitchException;
use App\Helpers\ResponseHelper;
use App\Services\ThirdPartyApi\BillerPaymentBase;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PowerController
{
    /**
     * @var Repository|Application|mixed
     */
    protected $terminalId;

    public function __construct()
    {
        $this->terminalId =config('topupspace.terminal_id');
    }

    /**
     * @return JsonResponse
     * @throws SwitchException
     */
    public function getPowerBillers():JsonResponse
    {
        try {
            $path ="api/v2/quickteller/billers";
            $response = (new BillerPaymentBase())->makeGetRequestAirtime($path);
            return ResponseHelper::success($response, 'Billers Fetch successfully');
        }  catch (\Exception $exception) {
            throw new SwitchException("Get Billers service not available at this time");
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws SwitchException
     */
    public function powerCustomerValidattion(Request $request):JsonResponse
    {
        try {
            $path = "api/v2/quickteller/customers/validations";
            $response = (new BillerPaymentBase())->makePostRequestAirtime($path, $this->constructValidationData($request));
            if ($response && isset($response['responseCode']) && $response['responseCode'] == '90000'){
                return ResponseHelper::success($response, 'customer Validation successful');
            }else {
                throw new SwitchException("Customer Validation service not available at this time");
            }
        } catch (\Exception $exception) {
            throw new SwitchException("Customer Validation  not available at this time");
        }
    }

    /**
     * @return JsonResponse
     * @throws SwitchException
     */
    public function powerPayment():JsonResponse
    {
        try {
            $path = "api/v2/quickteller/payments/advices";
            $response = (new BillerPaymentBase())->makePostRequestAirtime($path, $this->constructPaymentData($request));
            if ($response && isset($response['responseMessage']) && $response['responseMessage'] == 'Success'){
                return ResponseHelper::success($response, 'Power Payment successful');
            }else {
                throw new SwitchException("Power Payment service not available at this time");
            }
        } catch (\Exception $exception) {
            throw new SwitchException("Power Payment service not available at this time");
        }
    }

    /**
     * @param $request
     * @return array[]
     */
    public function constructValidationData($request):array
    {
        return [
           "customers"=>[
               "customerId"=>$request->meterNumber,
               "paymentCode"=>$request->paymentCode
           ]
        ];
    }

    /**
     * @param $request
     * @return array
     */
    public function constructPaymentData($request):array
    {
        return [
            "TerminalId"=>$this->terminalId,
            "paymentCode"=>"10902",
            "customerId"=>$request->meter_number,
            "customerMobile"=>$request->phone_number,
            "customerEmail"=>$request->email,
            "amount"=>$request->amount,
            "requestReference"=>"1194000023"
        ];
    }
}
