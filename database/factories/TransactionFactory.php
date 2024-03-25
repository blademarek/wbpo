<?php

namespace Database\Factories;

use App\Enums\TransactionStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'amount' => fake()->randomDigit(),
            'currency' => fake()->currencyCode(),
            'provider' => 'Paypal',
            'user_id' => User::factory(),
            'status' => TransactionStatus::NEW
        ];
    }
}
