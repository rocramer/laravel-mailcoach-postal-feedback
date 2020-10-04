<?php

namespace Rocramer\MailcoachPostalFeedback;

use Illuminate\Support\Arr;
use Spatie\Mailcoach\Events\WebhookCallProcessedEvent;
use Spatie\Mailcoach\Models\Send;
use Spatie\Mailcoach\Support\Config;
use Spatie\WebhookClient\Models\WebhookCall;
use Spatie\WebhookClient\ProcessWebhookJob;

class ProcessPostalWebhookJob extends ProcessWebhookJob
{
    public function __construct(WebhookCall $webhookCall)
    {
        parent::__construct($webhookCall);

        $this->queue = config('mailcoach.perform_on_queue.process_feedback_job');

        $this->connection = $this->connection ?? Config::getQueueConnection();
    }

    public function handle()
    {
        $payload = $this->webhookCall->payload;

        if (!$send = $this->getSend()) {
            return;
        };

        $postalEvent = PostalEventFactory::createForPayload($payload);

        $postalEvent->handle($send);

        event(new WebhookCallProcessedEvent($this->webhookCall));
    }

    protected function getSend(): ?Send
    {
        $messageId = Arr::get($this->webhookCall->payload, 'event-data.message.headers.message-id');

        if (!$messageId) {
            return null;
        }

        return Send::findByTransportMessageId($messageId);
    }
}
