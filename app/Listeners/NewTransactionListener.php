<?php

namespace App\Listeners;

use App\Events\TransactionCreated;
use Illuminate\Support\Facades\Log;

class NewTransactionListener
{
    public function handle(TransactionCreated $event): void
    {
        $transaction = $event->transaction;

        Log::info('Transaction created', ['transaction_id' => $transaction->id]);
    }
}
