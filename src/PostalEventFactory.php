<?php

namespace Rocramer\MailcoachPostalFeedback;

use Rocramer\MailcoachPostalFeedback\PostalEvents\ClickEvent;
use Rocramer\MailcoachPostalFeedback\PostalEvents\ComplaintEvent;
use Rocramer\MailcoachPostalFeedback\PostalEvents\PostalEvent;
use Rocramer\MailcoachPostalFeedback\PostalEvents\OpenEvent;
use Rocramer\MailcoachPostalFeedback\PostalEvents\OtherEvent;
use Rocramer\MailcoachPostalFeedback\PostalEvents\BounceEvent;

class PostalEventFactory
{
    protected static array $postalEvents = [
        ClickEvent::class,
        ComplaintEvent::class,
        OpenEvent::class,
        BounceEvent::class,
    ];

    public static function createForPayload(array $payload): PostalEvent
    {
        $postalEvent = collect(static::$postalEvents)
            ->map(fn (string $postalEventClass) => new $postalEventClass($payload))
            ->first(fn (PostalEvent $postalEvent) => $postalEvent->canHandlePayload());

        return $postalEvent ?? new OtherEvent($payload);
    }
}
