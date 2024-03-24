<?php

namespace App\Rules;

use App\Enums\TransactionStatus;
use App\Models\Transaction;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Log;

class ValidTransactionStatusTransition implements ValidationRule
{
    protected Transaction $currentTransaction;

    public function __construct($currentTransaction)
    {
        $this->currentTransaction = $currentTransaction;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validTransitions = [
            TransactionStatus::NEW => [TransactionStatus::PROCESSING],
            TransactionStatus::PROCESSING => [TransactionStatus::COMPLETED, TransactionStatus::FAILED],
            TransactionStatus::COMPLETED => [],
            TransactionStatus::FAILED => [TransactionStatus::PROCESSING]
        ];

        if (!in_array($value, $validTransitions[$this->currentTransaction->status])) {
            Log::info("Transaction status change not allowed for: {$this->currentTransaction->id}. old: $value new: {$this->currentTransaction->status}");
            $fail('Transaction status change not allowed');
        }
    }
}
