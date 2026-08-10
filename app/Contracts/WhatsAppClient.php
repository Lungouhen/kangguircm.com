<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Data\WhatsAppSendResult;

interface WhatsAppClient
{
    public function sendTemplate(string $recipient, string $template, string $language): WhatsAppSendResult;
}
