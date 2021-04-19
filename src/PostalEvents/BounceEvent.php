<?php

namespace Rocramer\MailcoachPostalFeedback\PostalEvents;

use Spatie\Mailcoach\Domain\Shared\Models\Send;

class BounceEvent extends PostalEvent
{
    public function canHandlePayload(): bool
    {
        if ($this->event !== 'MessageBounced' && $this->event !== 'MessageDeliveryFailed') {
            return false;
        };

        return true;
    }

    public function handle(Send $send)
    {
        $send->registerBounce($this->getTimestamp());
    }
}
