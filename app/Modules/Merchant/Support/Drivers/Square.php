<?php

namespace BT\Modules\Merchant\Support\Drivers;

use BT\Modules\Documents\Models\Invoice;
use BT\Modules\Merchant\Models\MerchantPayment;
use BT\Modules\Merchant\Support\MerchantDriverPayable;
use BT\Modules\Payments\Models\Payment as BTPayment;
use Square\Models\Builders\CheckoutOptionsBuilder;
use Square\Models\Builders\CreatePaymentLinkRequestBuilder;
use Square\Models\Builders\MoneyBuilder;
use Square\Models\Builders\QuickPayBuilder;
use Square\SquareClient;

class Square extends MerchantDriverPayable
{
    protected $isRedirect = true;

    public function getSettings()
    {
        return ['applicationId', 'accessToken', 'locationId', 'mode' => ['sandbox' => trans('bt.sandbox'), 'production' => trans('bt.production')]];
    }

    public function pay(Invoice $invoice)
    {
        // using Square hosted checkout page currently does not support a "cancel" or "back to merchant" redirect
        // user option is browser back button or close page , both of which are no help in returning to BT customer page..
        $apiContext = $this->getApiContext();

        $body = CreatePaymentLinkRequestBuilder::init()
            ->checkoutOptions(
                CheckoutOptionsBuilder::init()
                    ->redirectUrl(route('merchant.returnUrl', [$this->getName(), $invoice->url_key]))
                    ->build()
            )
            ->idempotencyKey(uniqid())
            ->quickPay(
                QuickPayBuilder::init(
                    $invoice->companyProfile->company . ' ' .trans('bt.invoice') . ' #' . $invoice->number,
                    MoneyBuilder::init()
                        ->amount($invoice->amount->balance * 100)
                        ->currency($invoice->currency_code)
                        ->build(),
                    $this->getSetting('locationId')
                )->build()
            )->build();

        $apiResponse = $apiContext->getCheckoutApi()->createPaymentLink($body);

        if ($apiResponse->isSuccess()) {
            $createPaymentLinkResponse = $apiResponse->getResult();
            return $createPaymentLinkResponse->getPaymentLink()->getUrl();

        } else {
            $errors = $apiResponse->getErrors();
            return redirect()->back()
                ->with('error', $errors ?? __('bt.order_response_error'));
            // Getting more response information
            // var_dump($apiResponse->getStatusCode());
            // var_dump($apiResponse->getHeaders());
        }
    }

    public function verify(Invoice $invoice)
    {
        //actual production redirect is supposedly appended like so...: https://www.redirect_url.com/?transactionId=tpEkkmWCsgZFGz9hcj88qeyreXEZY&orderId=tpEkkmWCsgZFGz9hcj88qeyreXEZY
        // sandbox does not return to redirect_url...

        $orderId = request('orderId');
        //$orderId = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx'; // copy from sandbox test payment

        $apiContext = $this->getApiContext();
        //have to get the order to get the payment info...
        $order = $apiContext->getOrdersApi()->retrieveOrder($orderId)->getResult()->getOrder();
        $tenders = $order->getTenders();
        $payment_id = $tenders[0]->getPaymentId();
        if ($order->getState() && ($order->getState() == 'OPEN' || $order->getState() == 'COMPLETED')) { // OPEN COMPLETED CANCELED DRAFT
            $payment = $apiContext->getPaymentsApi()->getPayment($payment_id)->getResult()->getPayment();
            if ($payment->getStatus() == 'COMPLETED') { // APPROVED, PENDING, COMPLETED, CANCELED, or FAILED
                $btPayment = BTPayment::create([
                    'client_id'         => $invoice->client->id,
                    'invoice_id'        => $invoice->id,
                    'amount'            => $payment->getTotalMoney()->getAmount() / 100,
                    'payment_method_id' => config('bt.onlinePaymentMethod'),
                ]);

                MerchantPayment::saveByKey($this->getName(), $btPayment->id, 'id', $payment_id);

                return true;
            }
        } else {
            return false;
        }
    }

    private function getApiContext()
    {
        $config = [
            'environment' => $this->getSetting('mode'), // Can only be 'sandbox' Or 'production'.
            'accessToken' => $this->getSetting('accessToken'),
        ];

        $apiContext = new SquareClient($config);

        return $apiContext;
    }
}
