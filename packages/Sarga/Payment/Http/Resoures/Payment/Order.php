<?php
/**
 * Created by PhpStorm.
 * User: merdan
 * Date: 9/22/2021
 * Time: 18:24
 */

namespace Sarga\Payment\Http\Resoures\Payment;


use Illuminate\Http\Resources\Json\JsonResource;
use function Symfony\Component\Translation\t;

class Order extends JsonResource
{
    public function getConfigData($field)
    {
        return core()->getConfigData('sales.paymentmethods.tfeb.' . $field);
    }

    public function toArray($request){
        return  [
            "RequestId" => $this->id,
            "Environment" => [
                "Merchant" => [
                    "Id" =>$this->getConfigData('merchant')
                ],
                "POI" => [
                    "Id" => $this->getConfigData('terminal'),
                    "Language" => "en-US"
                ],
                "Transport" => [
                    "MerchantFinalResponseUrl" => route('paymentmethod.tfeb.complete',['uid'=>auth()->user()->id]),
                    "ChallengeResponseUrl" => route('paymentmethod.tfeb.complete',['uid'=>auth()->user()->id]),
                    "ChallengeWindowSize" => 3,
                    "ChallengeResponseData"=> null,
                    "ThreeDSMethodNotificationUrl"=> "",
                    "MethodCompletion"=> false,
                    "Consent" => false,
                    "EndpointHostAddress" => "/orders/7b72093d-bb14-45b5-a6ec-a3ca5f6c2731"
                ],
                "SponsoredMerchant"=> null,
                "SponsoredMerchantPOI"=> null,
                "Card" => null,
                "CardRecipient"=> null,
                "Customer" => [
                    "Name" => $this->customer_first_name,
                    "Language" => "en-US",
                    "Email" => $this->customer_email.'@ozan.com.tm',
                    "HomePhone" => [
                        "cc" => "993",
                        "subscriber" => $this->customer_email
                    ],
                    "MobilePhone" => [
                        "cc" => "993",
                        "subscriber" => $this->customer_email
                    ],
                    "WorkPhone" => null
                ],

                "CustomerDevice" => [
                    "Browser" =>  [
                        "AcceptHeader" => "*/*",
                        "IpAddress" => request()->ip(),
                        "Language" => "en-US",
                        "ScreenColorDepth" => 48,
                        "ScreenHeight" => 1200,
                        "ScreenWidth" => 1900,
                        "UserAgentString" => "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.3"
                    ],
                    "MobileApp" => null,
                ],
                'BillingAddress' => CartAddress::make($this->billing_address),
                'ShippingAddress' => CartAddress::make($this->shipping_address),

            ],
            "Transaction" => [
                "InvoiceNumber" => "Acquirer",
                "Type" => "CRDP",
                "AdditionalService" => null,
                "TransactionText" => "ozan online sowda",
                "TotalAmount" => (double)$this->grand_total,
                "Currency" => "934",
                "CurrencyConversion"=>   null,
                "DetailedAmount"=> null,
                "AirlineItems"=> null,
                "MerchantOrderId" => $this->id,
                "AutoComplete" => true,
                "Instalment"=> null,
                "MerchantCategoryCode"=> null,
                "AntiMoneyLaundering"=> [
                    "SenderName"=> $this->customer_first_name,
                    "SenderDateOfBirth"=> null,
                    "SenderPlaceOfBirth"=> null,
                    "NationalIdentifier"=> null,
                    "NationalIdentifierCountry"=> null,
                    "NationalIdentifierExpiry"=> null,
                    "PassportNumber"=> "123-456",
                    "PassportIssuingCountry"=> null,
                    "PassportExpiry"=> "20291/12/01"
                ],
            ]
        ];
    }
}
