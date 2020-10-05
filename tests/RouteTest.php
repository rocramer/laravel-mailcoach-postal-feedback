<?php

namespace Rocramer\MailcoachPostalFeedback\Tests;

use Illuminate\Support\Facades\Route;

class RouteTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Route::postalFeedback('postal-feedback');
    }

    /** @test */
    public function it_provides_a_route_macro_to_handle_webhooks()
    {
        $validPayload = $this->getStub('messageBouncedWebhookContent');

        $response = $this->postJson('postal-feedback', $validPayload);

        $this->assertNotEquals(404, $response->getStatusCode());
    }
}
