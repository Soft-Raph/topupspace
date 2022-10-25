<?php

namespace App\Http\Controllers;

use App\Exceptions\SwitchException;
use App\Helpers\ResponseHelper;
use App\Services\ThirdPartyApi\BillerPaymentBase;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AirtimeController
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
     * @param Request $request
     * @return JsonResponse
     * @throws SwitchException
     */
    public function getAirtimeBillers(Request $request):JsonResponse
    {
        try {
            $categoryId= $request->category_id;
            $path ="api/v2/quickteller/categorys/{$categoryId}/billers";
            $response = (new BillerPaymentBase())->makeGetRequestAirtime($path);
            return ResponseHelper::success($response, 'Biller Category Fetch successfully');
        }  catch (\Exception $exception) {
            throw new SwitchException("Biller Category service not available at this time");
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws SwitchException
     */
    public function airtimePayment(Request $request): JsonResponse
    {
        try {
            $path = "api/v2/quickteller/payments/advices";
            $response = (new BillerPaymentBase())->makePostRequestAirtime($path, $this->constructData($request));
            if ($response && isset($response['responseMessage']) && $response['responseMessage'] == 'Success'){
                return ResponseHelper::success($response, 'Airtime successful');
            }else {
                throw new SwitchException("Airtime Payment service not available at this time");
            }
        } catch (\Exception $exception) {
            throw new SwitchException("Airtime Payment service not available at this time");
        }
    }

    /**
     * @param $request
     * @return array
     */
    public function constructData($request):array
    {
        return [
          "TerminalId"=>$this->terminalId,
            "paymentCode"=>"10902",
            "customerId"=>$request->phone_number,
            "customerMobile"=>$request->phone_number,
            "customerEmail"=>$request->email,
            "amount"=>$request->amount,
            "requestReference"=>"1194000023"
        ];
    }
}
