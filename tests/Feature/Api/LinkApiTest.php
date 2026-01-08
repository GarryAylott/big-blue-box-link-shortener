<?php

namespace Tests\Feature\Api;

use App\Models\Click;
use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LinkApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
        ]);

        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    public function test_can_generate_api_token(): void
    {
        $response = $this->postJson('/api/tokens', [
            'email' => 'admin@test.com',
            'password' => 'password123',
            'token_name' => 'my-api-token',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['token', 'type']);
    }

    public function test_cannot_generate_token_with_invalid_credentials(): void
    {
        $response = $this->postJson('/api/tokens', [
            'email' => 'admin@test.com',
            'password' => 'wrongpassword',
            'token_name' => 'test',
        ]);

        $response->assertStatus(401);
    }

    public function test_api_requires_authentication(): void
    {
        $response = $this->getJson('/api/links');

        $response->assertStatus(401);
    }

    public function test_can_list_links(): void
    {
        Link::create(['short_slug' => 'link1', 'target_url' => 'https://example.com']);
        Link::create(['short_slug' => 'link2', 'target_url' => 'https://example.org']);

        $response = $this->withToken($this->token)->getJson('/api/links');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
    }

    public function test_can_create_link_via_api(): void
    {
        $response = $this->withToken($this->token)->postJson('/api/links', [
            'short_slug' => 'api-link',
            'target_url' => 'https://example.com/api',
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('data.short_slug', 'api-link');
        $this->assertDatabaseHas('links', ['short_slug' => 'api-link']);
    }

    public function test_can_get_single_link(): void
    {
        Link::create(['short_slug' => 'single', 'target_url' => 'https://example.com']);

        $response = $this->withToken($this->token)->getJson('/api/links/single');

        $response->assertStatus(200);
        $response->assertJsonPath('data.short_slug', 'single');
    }

    public function test_can_update_link_via_api(): void
    {
        Link::create(['short_slug' => 'to-update', 'target_url' => 'https://old.com']);

        $response = $this->withToken($this->token)->putJson('/api/links/to-update', [
            'target_url' => 'https://new.com',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('links', [
            'short_slug' => 'to-update',
            'target_url' => 'https://new.com',
        ]);
    }

    public function test_can_delete_link_via_api(): void
    {
        Link::create(['short_slug' => 'to-delete', 'target_url' => 'https://example.com']);

        $response = $this->withToken($this->token)->deleteJson('/api/links/to-delete');

        $response->assertStatus(200);
        $this->assertDatabaseMissing('links', ['short_slug' => 'to-delete']);
    }

    public function test_can_get_link_stats(): void
    {
        $link = Link::create(['short_slug' => 'stats-test', 'target_url' => 'https://example.com']);
        Click::create(['slug' => 'stats-test', 'ip_address' => '127.0.0.1']);
        Click::create(['slug' => 'stats-test', 'ip_address' => '127.0.0.2']);
        Click::create(['slug' => 'stats-test', 'ip_address' => '127.0.0.3']);

        $response = $this->withToken($this->token)->getJson('/api/links/stats-test/stats');

        $response->assertStatus(200);
        $response->assertJsonPath('total_clicks', 3);
        $response->assertJsonStructure([
            'slug',
            'target_url',
            'short_url',
            'total_clicks',
            'clicks_today',
            'clicks_this_week',
            'clicks_this_month',
            'recent_clicks',
        ]);
    }

    public function test_api_validates_input(): void
    {
        $response = $this->withToken($this->token)->postJson('/api/links', [
            'short_slug' => 'invalid slug',
            'target_url' => 'not-a-url',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['short_slug', 'target_url']);
    }

    public function test_returns_404_for_nonexistent_link(): void
    {
        $response = $this->withToken($this->token)->getJson('/api/links/nonexistent');

        $response->assertStatus(404);
    }
}
