<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mollie\Laravel\Facades\Mollie;

class MolliePaymentController extends Controller
{
    public function preparePayment()
    {
        $payment = Mollie::api()->payments->create([
            "amount" => [
                "currency" => "EUR",
                "value" => "10.00" // You must send the correct number of decimals, thus we enforce the use of strings
            ],
            "description" => "Order #12345",
            "redirectUrl" => 'https://google.com',
            "webhookUrl" => 'http://sv.iqfx.nl/webhooks/mollie',
            "metadata" => [
                "order_id" => "12345",
            ],
        ]);

        // redirect customer to Mollie checkout page
        return redirect()->away($payment->getCheckoutUrl(), 303);
    }

    public function index()
    {
        return view('intro');
    }

    /**
     * After the customer has completed the transaction,
     * you can fetch, check and process the payment.
     * This logic typically goes into the controller handling the inbound webhook request.
     * See the webhook docs in /docs and on mollie.com for more information.
     */
}