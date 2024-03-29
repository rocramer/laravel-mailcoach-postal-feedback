<?php

namespace Rocramer\MailcoachPostalFeedback;

use Illuminate\Mail\Events\MessageSent;

class StoreTransportMessageId
{
    public function handle(MessageSent $event)
    {
        if (! isset($event->data['send'])) {
            return;
        }

        if (! $event->message->getHeaders()->has('Postal-Message-ID')) {
            return;
        }

        /** @var \Spatie\Mailcoach\Domain\Shared\Models\Send $send */
        $send = $event->data['send'];

        $transportMessageId = $event->message->getHeaders()->get('Postal-Message-ID')->getBodyAsString();

        $send->storeTransportMessageId($transportMessageId);
    }
}
