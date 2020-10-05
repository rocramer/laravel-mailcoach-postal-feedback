<?php

namespace Rocramer\MailcoachPostalFeedback;

use Illuminate\Http\Request;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator;
use Spatie\WebhookClient\WebhookConfig;

class PostalSignatureValidator implements SignatureValidator
{
    public function isValid(Request $request, WebhookConfig $config): bool
    {
        $dkim_public_key = config('mailcoach.postal_feedback.public_key');

        $rsa_key_pem = "-----BEGIN PUBLIC KEY-----\r\n" .
            chunk_split($dkim_public_key, 64) .
            "-----END PUBLIC KEY-----\r\n";
        $rsa_key = openssl_pkey_get_public($rsa_key_pem);

        $body = $request->getContent();

        $signature = $request->header($config->signatureHeaderName);
        $signature = base64_decode($signature);

        return openssl_verify($body, $signature, $rsa_key, OPENSSL_ALGO_SHA1);
    }
}
