<?php

namespace Rocramer\MailcoachPostalFeedback\PostalEvents;

use Spatie\Mailcoach\Domain\Shared\Models\Send;

class OpenEvent extends PostalEvent
{
    public function canHandlePayload(): bool
    {
        return $this->event === 'MessageLoaded';
    }

    public function handle(Send $send)
    {
        return $send->registerOpen($this->getTimestamp());
    }
}
