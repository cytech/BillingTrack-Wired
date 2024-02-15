<?php

namespace BT\Modules\Merchant\Support\Drivers;

use BT\Modules\Documents\Models\Invoice;
use BT\Modules\Merchant\Models\MerchantPayment;
use BT\Modules\Merchant\Support\MerchantDriver;
use BT\Modules\Payments\Models\Payment as BTPayment;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StripeV3 extends MerchantDriver
{
    protected $isRedirect = true;

    public function getSettings()
    {
        return ['publishableKey', 'secretKey'];
    }

    public function pay(Invoice $invoice)
    {
        \Stripe\Stripe::setApiKey($this->getSetting('secretKey'));

        $description = __('bt.invoice') . ' ' . __('bt.from') . ' : ' . $invoice->companyProfile->company . ' ' . __('bt.to') . ' : ' . $invoice->client->name;

        $lineItems[] = [
            'price_data' => [
                'currency'     => $invoice->currency_code,
                'product_data' => [
                    'name'        => trans('bt.invoice') . ' #' . $invoice->number,
                    'description' => $description,
                ],
                'unit_amount'  => $invoice->amount->balance * 100,
            ],
            'quantity'   => 1,
        ];
        $session = \Stripe\Checkout\Session::create([
            'line_items'  => $lineItems,
            'mode'        => 'payment',
            'success_url' => route('merchant.returnUrl', [$this->getName(), $invoice->url_key]) . "?session_id={CHECKOUT_SESSION_ID}",
            'cancel_url'  => route('merchant.cancelUrl', [$this->getName(), $invoice->url_key]) . "?session_id={CHECKOUT_SESSION_ID}",
        ]);

        return $session->url;
    }

    public function verify(Invoice $invoice)
    {
        \Stripe\Stripe::setApiKey($this->getSetting('secretKey'));

        $sessionId = request('session_id'); //stripeV3 session_id

        try {
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
            if (!$session) {
                throw new NotFoundHttpException;
            }

            $payment = BTPayment::create([
                'client_id'         => $invoice->client->id,
                'invoice_id'        => $invoice->id,
                'amount'            => $session->amount_total / 100,
                'payment_method_id' => config('bt.onlinePaymentMethod'),
            ]);

            MerchantPayment::saveByKey($this->getName(), $payment->id, 'id', $session->id);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


    public function webhook(Invoice $invoice)
    {
        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response('', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response('', 400);
        }

// Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;

                $payment = BTPayment::create([
                    'client_id'         => $invoice->client->id,
                    'invoice_id'        => $invoice->id,
                    'amount'            => $session->amount_total / 100,
                    'payment_method_id' => config('bt.onlinePaymentMethod'),
                ]);

            // ... handle other event types
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        return response('');
    }
}
