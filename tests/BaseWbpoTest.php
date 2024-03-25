<?php

namespace Tests;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

class BaseWbpoTest extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected Transaction $transaction;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function createUser(): static
    {
        $this->user = User::factory()->create();
        return $this;
    }

    protected function createTransaction(): static
    {
        if (isset($this->user)) {
            $this->transaction = Transaction::factory()->create();
        }

        return $this;
    }

    protected function getTransactionDetails(): array
    {
        $transactionDetails = [];

        if (isset($this->user)) {
            $transactionDetails = [
                'amount' => 100,
                'currency' => 'USD',
                'provider' => 'Stripe',
                'user_id' => $this->user->id
            ];
        }

        return $transactionDetails;
    }
}
