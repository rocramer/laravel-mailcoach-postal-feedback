<?php

namespace Rocramer\MailcoachPostalFeedback\PostalEvents;

use Carbon\Carbon;
use DateTimeInterface;
use Spatie\Mailcoach\Domain\Shared\Models\Send;

abstract class PostalEvent
{
    protected array $payload;

    protected string $event;

    public function __construct(array $payload)
    {
        $this->payload = $payload;

        $this->event = $this->payload['event'];
    }

    abstract public function canHandlePayload(): bool;

    abstract public function handle(Send $send);

    public function getTimestamp(): ?DateTimeInterface
    {
        $timestamp = $this->payload['timestamp'];

        return $timestamp ? Carbon::createFromTimestamp($timestamp) : null;
    }
}
