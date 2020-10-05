<?php

namespace Rocramer\MailcoachPostalFeedback\Tests;

use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use Rocramer\MailcoachPostalFeedback\ProcessPostalWebhookJob;
use Rocramer\MailcoachPostalFeedback\Tests\factories\SendFactory;
use Spatie\Mailcoach\Enums\SendFeedbackType;
use Spatie\Mailcoach\Events\WebhookCallProcessedEvent;
use Spatie\Mailcoach\Models\CampaignClick;
use Spatie\Mailcoach\Models\CampaignLink;
use Spatie\Mailcoach\Models\CampaignOpen;
use Spatie\Mailcoach\Models\Send;
use Spatie\Mailcoach\Models\SendFeedbackItem;
use Spatie\WebhookClient\Models\WebhookCall;

class ProcessPostalWebhookJobTest extends TestCase
{
    private WebhookCall $webhookCall;

    private Send $send;

    public function setUp(): void
    {
        parent::setUp();

        $this->webhookCall = WebhookCall::create([
            'name' => 'postal',
            'payload' => $this->getStub('messageBouncedWebhookContent'),
        ]);

        $this->send = (new SendFactory())->create([
            'transport_message_id' => '3b1b3598-136a-43e9-b22b-8b35f5cb486f@rp.postal.example.de',
        ]);

        $this->send->campaign->update([
            'track_opens' => true,
            'track_clicks' => true,
        ]);
    }

    /** @test */
    public function it_processes_a_postal_bounce_webhook_call()
    {
        (new ProcessPostalWebhookJob($this->webhookCall))->handle();

        $this->assertEquals(1, SendFeedbackItem::count());

        tap(SendFeedbackItem::first(), function (SendFeedbackItem $sendFeedbackItem) {
            $this->assertEquals(SendFeedbackType::BOUNCE, $sendFeedbackItem->type);
            $this->assertEquals(Carbon::createFromTimestamp(1601910800.000000), $sendFeedbackItem->created_at);
            $this->assertTrue($this->send->is($sendFeedbackItem->send));
        });
    }

    /** @test */
    public function it_processes_a_postal_click_webhook_call()
    {
        $this->webhookCall->update(['payload' => $this->getStub('messageLinkClickedContent')]);
        (new ProcessPostalWebhookJob($this->webhookCall))->handle();

        $this->assertEquals(1, CampaignLink::count());
        $this->assertEquals('https://www.example.de/blog/some-post', CampaignLink::first()->url);
        $this->assertCount(1, CampaignLink::first()->clicks);
        tap(CampaignLink::first()->clicks->first(), function (CampaignClick $campaignClick) {
            $this->assertEquals(Carbon::createFromTimestamp(1601910800.000000), $campaignClick->created_at);
        });
    }

    /** @test */
    public function it_can_process_a_postal_open_webhook_call()
    {
        $this->webhookCall->update(['payload' => $this->getStub('messageLoadedContent')]);
        (new ProcessPostalWebhookJob($this->webhookCall))->handle();

        $this->assertCount(1, $this->send->campaign->opens);
        tap($this->send->campaign->opens->first(), function (CampaignOpen $campaignOpen) {
            $this->assertEquals(Carbon::createFromTimestamp(1601910800.000000), $campaignOpen->created_at);
        });
    }

    /** @test */
    public function it_fires_an_event_after_processing_the_webhook_call()
    {
        Event::fake();

        $this->webhookCall->update(['payload' => $this->getStub('messageLoadedContent')]);
        (new ProcessPostalWebhookJob($this->webhookCall))->handle();

        Event::assertDispatched(WebhookCallProcessedEvent::class);
    }

    /** @test */
    public function it_will_not_handle_unrelated_events()
    {
        $this->webhookCall->update(['payload' => $this->getStub('otherWebhookContent')]);
        (new ProcessPostalWebhookJob($this->webhookCall))->handle();

        $this->assertEquals(0, CampaignLink::count());
        $this->assertEquals(0, CampaignOpen::count());
        $this->assertEquals(0, SendFeedbackItem::count());
    }

    /** @test */
    public function it_does_nothing_when_it_cannot_find_the_transport_message_id()
    {
        $data = $this->webhookCall->payload;

        $data['payload']['original_message']['message_id'] = 'some-other-id';

        $this->webhookCall->update([
            'payload' => $data,
        ]);

        $job = new ProcessPostalWebhookJob($this->webhookCall);

        $job->handle();

        $this->assertEquals(0, SendFeedbackItem::count());
    }
}
