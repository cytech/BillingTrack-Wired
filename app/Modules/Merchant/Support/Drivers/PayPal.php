<?php

namespace BT\Modules\Merchant\Support\Drivers;

use BT\Modules\Invoices\Models\Invoice;
use BT\Modules\Merchant\Models\MerchantPayment;
use BT\Modules\Merchant\Support\MerchantDriverPayable;
use BT\Modules\Payments\Models\Payment as BTPayment;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPal extends MerchantDriverPayable
{
    protected $isRedirect = true;

    public function getSettings()
    {
        return ['clientId', 'clientSecret', 'mode' => ['sandbox' => trans('bt.sandbox'), 'live' => trans('bt.live')]];
    }

    public function pay(Invoice $invoice)
    {
        $apiContext = $this->getApiContext();

        $response = $apiContext->createOrder([
            "intent"              => "CAPTURE",
            "application_context" => [
                "return_url" => route('merchant.returnUrl', [$this->getName(), $invoice->url_key]),
                "cancel_url" => route('merchant.cancelUrl', [$this->getName(), $invoice->url_key]),
            ],
            "purchase_units"      => [
                0 => [
                    "amount"     => [
                        "currency_code" => $invoice->currency_code,
                        "value"         => $invoice->amount->balance + 0
                    ],
                     //enabling this may return error if attempt to pay twice on same invoice
                     //"invoice_id" => trans('bt.invoice') . ' #' . $invoice->number
                ]
            ]
        ]);

        if (isset($response['id']) && $response['id'] != null) {
            // redirect to approve href
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return $links['href'];
                }
            }

            return redirect()->back()
                ->with('error', __('bt.order_not_approved'));

        } else {
            return redirect()->back()
                ->with('error', $response['message'] ?? __('bt.order_response_error'));
        }
    }

    public function verify(Invoice $invoice)
    {
        $paymentId = request('token');
        $apiContext = $this->getApiContext();
        $payment = $apiContext->capturePaymentOrder($paymentId);
        if (isset($payment['status']) && $payment['status'] == 'COMPLETED') {
            foreach ($payment['purchase_units'] as $unit) {
                foreach ($unit['payments']['captures'] as $capture) {
                    $btPayment = BTPayment::create([
                        'client_id'         => $invoice->client->id,
                        'invoice_id'        => $invoice->id,
                        'amount'            => $capture['amount']['value'],
                        'payment_method_id' => config('bt.onlinePaymentMethod'),
                    ]);

                    MerchantPayment::saveByKey($this->getName(), $btPayment->id, 'id', $capture['id']);
                }
            }
            return true;
        } else {
            return false;
        }
    }

    private function getApiContext()
    {
        $config = [
            'mode'           => $this->getSetting('mode'), // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
            'sandbox'        => [
                'client_id'     => $this->getSetting('clientId'),
                'client_secret' => $this->getSetting('clientSecret'),
                'app_id'        => '',
            ],
            'live'           => [
                'client_id'     => $this->getSetting('clientId'),
                'client_secret' => $this->getSetting('clientSecret'),
                'app_id'        => '',
            ],
            // set in config/paypal.php
            'payment_action' => config('paypal.payment_action'),
            'currency'       => config('paypal.currency'),
            'notify_url'     => config('paypal.notify_url'),
            'locale'         => config('paypal.locale'),
            'validate_ssl'   => config('paypal.validate_ssl'),

        ];
        $apiContext = new PayPalClient;
        $apiContext->setApiCredentials($config);
        $apiContext->getAccessToken();

        return $apiContext;
    }
}
/*
//response from capturePaymentOrder
$testresponse = [
    "id"             => "XXXXXXXXXXXXXXX",
    "status"         => "COMPLETED",
    "purchase_units" => [
        0 => [
            "reference_id" => "default",
            "shipping"     => [
                "name"    => [
                    "full_name" => "test buyer"
                ],
                "address" => [
                    "address_line_1" => "1 Main St",
                    "admin_area_2"   => "San Jose",
                    "admin_area_1"   => "CA",
                    "postal_code"    => "95131",
                    "country_code"   => "US",
                ]
            ],
            "payments"     => [
                "captures" => [
                    0 => [
                        "id"                          => "XXXXXXXXXXXXXXX",
                        "status"                      => "COMPLETED",
                        "amount"                      => [
                            "currency_code" => "USD",
                            "value"         => "34.78",
                        ],
                        "final_capture"               => true,
                        "seller_protection"           => [
                            "status"             => "ELIGIBLE",
                            "dispute_categories" => [
                                0 => "ITEM_NOT_RECEIVED",
                                1 => "UNAUTHORIZED_TRANSACTION",
                            ]
                        ],
                        "seller_receivable_breakdown" => [
                            "gross_amount" => [
                                "currency_code" => "USD",
                                "value"         => "34.78",
                            ],
                            "paypal_fee"   => [
                                "currency_code" => "USD",
                                "value"         => "1.70",
                            ],
                            "net_amount"   => [
                                "currency_code" => "USD",
                                "value"         => "33.08",
                            ]
                        ],
                        "links"                       => [
                            0 => [
                                "href"   => "https://api.sandbox.paypal.com/v2/payments/captures/XXXXXXXXXXXXXXX",
                                "rel"    => "self",
                                "method" => "GET",
                            ],
                            1 => [
                                "href"   => "https://api.sandbox.paypal.com/v2/payments/captures/XXXXXXXXXXXXXXX/refund",
                                "rel"    => "refund",
                                "method" => "POST",
                            ],
                            2 => [
                                "href"   => "https://api.sandbox.paypal.com/v2/checkout/orders/XXXXXXXXXXXXXXX",
                                "rel"    => "up",
                                "method" => "GET",
                            ]
                        ],
                        "create_time"                 => "2022-03-15T21:03:12Z",
                        "update_time"                 => "2022-03-15T21:03:12Z",
                    ]
                ]
            ]
        ]
    ],
    "payer"          => [
        "name"          => [
            "given_name" => "test",
            "surname"    => "buyer",
        ],
        "email_address" => "XXX@XXX.com",
        "payer_id"      => "XXXXXXXXX",
        "address"       => [
            "country_code" => "US"
        ]
    ],
    "links"          => [
        0 => [
            "href"   => "https://api.sandbox.paypal.com/v2/checkout/orders/XXXXXXXXXXXXXXX",
            "rel"    => "self",
            "method" => "GET",
        ]
    ]
];*/
