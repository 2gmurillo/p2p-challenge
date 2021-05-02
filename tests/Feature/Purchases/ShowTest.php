<?php

namespace Tests\Feature\Purchases;

use Tests\TestCase;

class ShowTest extends TestCase
{
    /** @test */
    public function itCanGetShowViewForFirstPaymentMethod()
    {
        $response = $this->get($this->route('first'));
        $response->assertStatus(200);
        $response->assertViewIs('purchases.show');
    }

    /** @test */
    public function itCanGetShowViewForSecondPaymentMethod()
    {
        $response = $this->get($this->route('second'));
        $response->assertStatus(200);
        $response->assertViewIs('purchases.show');
    }

    /** @test */
    public function itCannotShowAPaymentWhenParameterIsNotValid()
    {
        $response = $this->get($this->route('wrong method'));
        $response->assertNotFound();
    }

    private function route(string $payment): string
    {
        return route('purchases.show', ['payment' => $payment]);
    }
}
