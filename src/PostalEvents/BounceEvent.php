<?php

namespace Rocramer\MailcoachPostalFeedback\PostalEvents;

use Spatie\Mailcoach\Models\Send;

class BounceEvent extends PostalEvent
{
    public function canHandlePayload(): bool
    {
        if ($this->event !== 'MessageBounced') {
            return false;
        };

        return true;
    }

    public function handle(Send $send)
    {
        $send->registerBounce($this->getTimestamp());
    }
}
