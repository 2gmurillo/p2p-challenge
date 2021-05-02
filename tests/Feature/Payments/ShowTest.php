<?php

namespace Tests\Feature\Payments;

use Tests\TestCase;

class ShowTest extends TestCase
{
    /** @test */
    public function itCanGetShowViewForFirstPaymentMethod()
    {
        $response = $this->get($this->route('first'));
        $response->assertStatus(200);
        $response->assertViewIs('payments.show');
    }

    /** @test */
    public function itCanGetShowViewForSecondPaymentMethod()
    {
        $response = $this->get($this->route('second'));
        $response->assertStatus(200);
        $response->assertViewIs('payments.show');
    }

    /** @test */
    public function itCannotShowAPaymentWhenParameterIsNotValid()
    {
        $response = $this->get($this->route('wrong method'));
        $response->assertViewIs('errors.404');
    }

    private function route(string $payment): string
    {
        return route('payments.show', ['payment' => $payment]);
    }
}
