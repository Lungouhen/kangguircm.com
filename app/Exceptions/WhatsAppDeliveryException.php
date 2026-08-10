<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

class WhatsAppDeliveryException extends RuntimeException
{
    public function __construct(public readonly string $failureCode, string $message='WhatsApp delivery failed.')
    {
        parent::__construct($message);
    }
}
