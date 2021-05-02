<?php

namespace Tests\Feature\Purchases;

use App\Constants\ShippingMethods;
use Tests\TestCase;

class PrepareTest extends TestCase
{
    /** @test */
    public function itCanGetPrepareView()
    {
        $preparePaymentData = $this->preparePaymentData();
        $response = $this->get($this->route($preparePaymentData));
        $response->assertStatus(200);
        $response->assertViewIs('purchases.prepare');
        $response->assertViewHas('totalAmount', 16.5);
    }

    /**
     * @test
     * @dataProvider notValidDataProvider
     *
     * @param string $field
     * @param mixed|null $value
     */
    public function itCannotPrepareAPaymentWhenDataIsNotValid(string $field, $value = null)
    {
        $preparePaymentData = $this->preparePaymentData();
        $preparePaymentData[$field] = $value;
        $response = $this->get($this->route($preparePaymentData));
        $response->assertRedirect();
        $response->assertSessionHasErrors($field);
    }

    /**
     * @return array
     */
    public function notValidDataProvider(): array
    {
        return [
            'Test amount is required' => ['amount', null],
            'Test amount is numeric' => ['amount', 'string'],
            'Test amount is less than 1' => ['amount', 0],
            'Test distance is required' => ['distance', null],
            'Test distance is numeric' => ['distance', 'string'],
            'Test distance is less than 1' => ['distance', 0],
            'Test weight is required' => ['weight', null],
            'Test weight is numeric' => ['weight', 'string'],
            'Test weight is less than 1' => ['weight', 0],
            'Test shipping_method is required' => ['shipping_method', null],
            'Test shipping_method is in shipping methods' => ['shipping_method', 'wrong method'],
        ];
    }

    private function route(array $preparePaymentData): string
    {
        return route('purchases.prepare', $preparePaymentData);
    }

    private function preparePaymentData(): array
    {
        return [
            'amount' => 12,
            'distance' => 4,
            'weight' => 5,
            'shipping_method' => ShippingMethods::BASIC,
        ];
    }
}
