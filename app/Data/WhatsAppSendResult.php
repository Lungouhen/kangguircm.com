<?php

declare(strict_types=1);

namespace App\Data;

final readonly class WhatsAppSendResult
{
    public function __construct(public string $messageId) {}
}
