<?php

namespace App\Services;

use App\Interfaces\PaymentServiceInterface;

class DefaultPaymentService implements PaymentServiceInterface
{
    public function createPaymentLink(): string
    {
        return 'https://example.com/payment-link';
    }
}
