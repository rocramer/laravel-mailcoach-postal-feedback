<?php

namespace Rocramer\MailcoachPostalFeedback;

use Illuminate\Support\Arr;
use Spatie\Mailcoach\Domain\Campaign\Events\WebhookCallProcessedEvent;
use Spatie\Mailcoach\Domain\Shared\Models\Send;
use Spatie\Mailcoach\Domain\Shared\Support\Config;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;
use Spatie\WebhookClient\Models\WebhookCall;

class ProcessPostalWebhookJob extends ProcessWebhookJob
{
    public function __construct(WebhookCall $webhookCall)
    {
        parent::__construct($webhookCall);

        $this->queue = config('mailcoach.campaigns.perform_on_queue.process_feedback_job');

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
        $messageId = Arr::get($this->webhookCall->payload, 'payload.message.message_id');

        if (!$messageId) {
            $messageId = Arr::get($this->webhookCall->payload, 'payload.original_message.message_id');
        }

        if (!$messageId) {
            return null;
        }

        return Send::findByTransportMessageId($messageId);
    }
}
