<?php

namespace App\Http\Controllers;

use App\Exceptions\SwitchException;
use App\Helpers\ResponseHelper;
use App\Services\ThirdPartyApi\IdentityVerificationBase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class IdentityVerificationController extends Controller
{

    const PATH = "verifications";

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws SwitchException
     */
    public function identityVerification(Request $request): JsonResponse
    {
        try {
            $response = (new IdentityVerificationBase())->makePostRequest(self::PATH, $this->constructData($request));
            if ($response && isset($response['status']) && $response['status'] !== 'VERIFIED'){
                return ResponseHelper::error(422, 'Invalid verification details');
            }
            if ($response && isset($response['status']) && $response['status'] == 'VERIFIED'){
                return ResponseHelper::success($response, 'Identity Verification successful');
            }else {
                throw new SwitchException("Identity Verification service not available at this time");
            }
        } catch (\Exception $exception) {
            throw new SwitchException("Identity Verification service not available at this time");
        }
    }

    /**
     * @param $request
     * @return array|array[]
     */
    public function constructData($request):array
    {
        $subData = [
            "type"=>"INDIVIDUAL",
            "phone" =>$request->user()->phone_number,
            "lastName" => $request->user()->last_name,
            "firstName" => $request->user()->first_name,
            "birthDate" =>$request->user()->birth_date,
        ];
        if ($request->type === "BVN"){
            return [
                $subData,
                "verificationRequests"=>[
                    "type"=>"BVN",
                    "identityNumber"=>$request->user()->bvn
                ]
            ];
        }
        if ($request->type === "DRIVERS_LICENCE"){
            return [
                $subData,
                "verificationRequests"=>[
                    "type"=>"DRIVERS_LICENCE",
                    "identityNumber"=>$request->user()->drl
                ]
            ];
        }
        if ($request->type === "NIN"){
            return [
                $subData,
                "verificationRequests"=>[
                    "type"=>"NIN",
                    "identityNumber"=>$request->user()->nin
                ],
                "documents"=>[
                    "fileName"=>$request->user()->ninfile,
                    "description"=>"NIMC Slip"
                ]
            ];
        }
        if ($request->type === "PASSPORT"){
            return [
                $subData,
                "verificationRequests"=>[
                    "type"=>"PASSPORT",
                    "identityNumber"=>$request->user()->passport
                ]
            ];
        }
        if ($request->type === "BANK_ACCOUNT"){
            return [
                $subData,
                "verificationRequests"=>[
                    "type"=>"BANK_ACCOUNT",
                    "accountNumber"=>$request->user()->account_number,
                    "bankCode"=>$request->user()->bank_code,
                    "country"=>"NGA",
                ]
            ];
        }
        return [];
    }
}
