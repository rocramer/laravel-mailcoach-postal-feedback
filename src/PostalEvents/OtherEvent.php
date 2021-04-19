<?php

namespace Rocramer\MailcoachPostalFeedback\PostalEvents;

use Spatie\Mailcoach\Domain\Shared\Models\Send;

class OtherEvent extends PostalEvent
{
    public function canHandlePayload(): bool
    {
        return true;
    }

    public function handle(Send $send)
    {
    }
}
