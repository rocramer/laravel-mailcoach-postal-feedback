<?php

namespace Rocramer\MailcoachPostalFeedback;

use Illuminate\Http\Request;
use Spatie\WebhookClient\WebhookProcessor;

class PostalWebhookController
{
    public function __invoke(Request $request)
    {
        $webhookConfig = PostalWebhookConfig::get();

        (new WebhookProcessor($request, $webhookConfig))->process();

        return response()->json(['message' => 'ok']);
    }
}
