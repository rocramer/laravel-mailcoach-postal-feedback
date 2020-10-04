<?php

namespace Rocramer\MailcoachPostalFeedback;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator;
use Spatie\WebhookClient\WebhookConfig;

class PostalSignatureValidator implements SignatureValidator
{
    public function isValid(Request $request, WebhookConfig $config): bool
    {
        // TODO: validate signature
        return $request->input('signature.signature') === hash_hmac(
            'sha256',
            sprintf('%s%s', $request->input('signature.timestamp'), $request->input('signature.token')),
            $config->signingSecret
        );
    }
}
