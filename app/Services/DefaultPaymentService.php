<?php

namespace App\Services;

use App\Interfaces\PaymentServiceInterface;

class DefaultPaymentService implements PaymentServiceInterface
{
    public function createPaymentLink(array $data): string
    {
        return 'https://example.com/payment-link';
    }
}
