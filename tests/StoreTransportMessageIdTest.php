<?php

namespace Rocramer\MailcoachPostalFeedback\Tests;

use Illuminate\Mail\Events\MessageSent;
use Rocramer\MailcoachPostalFeedback\Tests\factories\SendFactory;
use Spatie\Mailcoach\Domain\Shared\Models\Send;
use Swift_Message;

class StoreTransportMessageIdTest extends TestCase
{
    /** @test * */
    public function it_stores_the_message_id_from_the_transport()
    {
        $pendingSend = Send::factory()->create();
        $message = new Swift_Message('Test', 'body');
        $message->getHeaders()->addTextHeader('Postal-Message-ID', '1234');

        event(new MessageSent($message, [
            'send' => $pendingSend,
        ]));

        tap($pendingSend->fresh(), function (Send $send) {
            $this->assertEquals('1234', $send->transport_message_id);
        });
    }
}
