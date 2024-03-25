<?php

namespace App\Enums;

class TransactionStatus
{
    public const NEW = 0;
    public const PROCESSING = 1;
    public const COMPLETED = 2;
    public const FAILED = 3;
}
