<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Http\Controllers\Concerns\Constants\ShippingMethods;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PreparePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:1'],
            'distance' => ['required', 'numeric', 'min:1'],
            'weight' => ['required', 'numeric', 'min:1'],
            'shipping_method' => ['required', Rule::in(ShippingMethods::toArray())],
        ];
    }
}
