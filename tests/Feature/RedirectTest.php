<?php

namespace Tests\Feature;

use App\Models\Click;
use App\Models\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_slug_redirects_correctly(): void
    {
        $link = Link::create([
            'short_slug' => 'test-link',
            'target_url' => 'https://example.com',
        ]);

        $response = $this->get('/test-link');

        $response->assertRedirect('https://example.com');
        $response->assertStatus(302);
    }

    public function test_click_is_tracked_on_redirect(): void
    {
        Link::create([
            'short_slug' => 'tracked',
            'target_url' => 'https://example.com',
        ]);

        $this->assertDatabaseCount('clicks', 0);

        $this->get('/tracked');

        $this->assertDatabaseCount('clicks', 1);
        $this->assertDatabaseHas('clicks', ['slug' => 'tracked']);
    }

    public function test_invalid_slug_returns_404(): void
    {
        $response = $this->get('/nonexistent-slug');

        $response->assertNotFound();
    }

    public function test_click_stores_referrer_and_user_agent(): void
    {
        Link::create([
            'short_slug' => 'metadata',
            'target_url' => 'https://example.com',
        ]);

        $this->get('/metadata', [
            'HTTP_REFERER' => 'https://twitter.com',
            'HTTP_USER_AGENT' => 'TestBrowser/1.0',
        ]);

        $click = Click::first();
        $this->assertEquals('https://twitter.com', $click->referrer);
        $this->assertEquals('TestBrowser/1.0', $click->user_agent);
    }
}
