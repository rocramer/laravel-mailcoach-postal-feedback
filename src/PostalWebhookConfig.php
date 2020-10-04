<?php

namespace Rocramer\MailcoachPostalFeedback;

use Spatie\WebhookClient\Models\WebhookCall;
use Spatie\WebhookClient\WebhookConfig;
use Spatie\WebhookClient\WebhookProfile\ProcessEverythingWebhookProfile;

class PostalWebhookConfig
{
    public static function get(): WebhookConfig
    {
        $config = config('mailcoach.postal_feedback');

        return new WebhookConfig([
            'name' => 'postal-feedback',
            'signing_secret' => $config['signing_secret'] ?? '',
            'header_name' => $config['header_name'] ?? 'x-postal-signature',
            'signature_validator' => $config['signature_validator'] ?? PostalSignatureValidator::class,
            'webhook_profile' =>  $config['webhook_profile'] ?? ProcessEverythingWebhookProfile::class,
            'webhook_model' => $config['webhook_model'] ?? WebhookCall::class,
            'process_webhook_job' => $config['process_webhook_job'] ?? ProcessPostalWebhookJob::class,
        ]);
    }
}
