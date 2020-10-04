<?php

namespace Rocramer\MailcoachPostalFeedback;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class MailcoachPostalFeedbackServiceProvider extends ServiceProvider
{
    public function register()
    {
        Route::macro('postalFeedback', fn (string $url) => Route::post($url, '\\' . PostalWebhookController::class));

        Event::listen(MessageSent::class, StoreTransportMessageId::class);
    }
}
