<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Constants\UserRoles;
use App\ExternalServices\PaymentMethods\FirstPaymentMethod;
use App\ExternalServices\PaymentMethods\SecondPaymentMethod;
use App\Constants\DollarPaymentCharge;
use App\Constants\ShippingMethods;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PurchaseController extends Controller
{
    public function prepare(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'amount' => ['required', 'numeric', 'min:1'],
                'distance' => ['required', 'numeric', 'min:1'],
                'weight' => ['required', 'numeric', 'min:1'],
                'shipping_method' => ['required', Rule::in(ShippingMethods::toArray())],
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $shippingMethod = $request->shipping_method;

        switch ($shippingMethod) {
            case ShippingMethods::PREMIUM:
                $shippingMethodCharge = DollarPaymentCharge::SHIPPING_METHODS[ShippingMethods::PREMIUM];
                break;
            case ShippingMethods::EXPRESS:
                $shippingMethodCharge = DollarPaymentCharge::SHIPPING_METHODS[ShippingMethods::EXPRESS];
                break;
            default:
                $shippingMethodCharge = DollarPaymentCharge::SHIPPING_METHODS[ShippingMethods::BASIC];
        }

        $distanceCharge = $request->distance * DollarPaymentCharge::DISTANCE;
        $weightCharge = $request->weight * DollarPaymentCharge::WEIGHT;
        $totalAmount = $distanceCharge + $weightCharge + $shippingMethodCharge + $request->amount;

        return view(
            'purchases.prepare',
            [
                'totalAmount' => $totalAmount,
                'shippingMethod' => $shippingMethod,
            ]
        );
    }

    public function pay(Request $request): RedirectResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'payment_method' => ['required', Rule::in(array_keys(config('payment-methods')))],
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $users = collect(config('users'));
        $sellers = $users->where('role', UserRoles::ASSISTANT);

        if ($sellers) {
            $customer = $users->where('role', UserRoles::CUSTOMER)->random(); // Instead of auth()->user();

            Mail::send(
                'emails.purchases.prepared',
                [
                    'name' => $customer['name'],
                    'email' => $customer['email'],
                    'shippingMethod' => $request->shipping_method,
                    'totalAmount' => $request->total_amount,
                    'paymentMethod' => $request->payment_method,
                ],
                function ($mail) use ($sellers) {
                    $mail->from(config('mail.from.address'));
                    foreach ($sellers as $seller) {
                        $mail->to($seller['email'])->subject('lead');
                    }
                }
            );
        }

        $requestData = [
            'amount' => $request->total_amount,
            'description' => $request->description,
            'return_url' => route('purchases.show', $request->payment_method),
        ];

        if ('first' === $request->payment_method) {
            $request->validate(
                [
                    'description' => ['required', 'string', 'min:3', 'max:80'],
                ]
            );
            $paymentMethod = new FirstPaymentMethod();

            return $paymentMethod->paymentRequest($requestData);
        } else {
            $paymentMethod = new SecondPaymentMethod();
            $paymentMethod->processPayment((float)$request->total_amount);

            return redirect($requestData['return_url']);
        }
    }

    public function show(string $payment): View
    {
        if (!in_array($payment, array_keys(config('payment-methods')))) {
            abort(404);
        }

        return view('purchases.show', compact('payment'));
    }
}
