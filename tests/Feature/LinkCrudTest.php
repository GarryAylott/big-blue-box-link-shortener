<?php

namespace Tests\Feature;

use App\Models\Click;
use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LinkCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
        ]);
    }

    public function test_can_view_links_index(): void
    {
        Link::create(['short_slug' => 'test1', 'target_url' => 'https://example.com']);
        Link::create(['short_slug' => 'test2', 'target_url' => 'https://example.org']);

        $response = $this->actingAs($this->user)->get('/admin/links');

        $response->assertStatus(200);
        $response->assertSee('test1');
        $response->assertSee('test2');
    }

    public function test_can_create_link(): void
    {
        $response = $this->actingAs($this->user)->post('/admin/links', [
            'short_slug' => 'new-link',
            'target_url' => 'https://example.com/page',
        ]);

        $response->assertRedirect('/admin/links');
        $this->assertDatabaseHas('links', [
            'short_slug' => 'new-link',
            'target_url' => 'https://example.com/page',
        ]);
    }

    public function test_cannot_create_duplicate_slug(): void
    {
        Link::create(['short_slug' => 'existing', 'target_url' => 'https://example.com']);

        $response = $this->actingAs($this->user)->post('/admin/links', [
            'short_slug' => 'existing',
            'target_url' => 'https://example.org',
        ]);

        $response->assertSessionHasErrors('short_slug');
    }

    public function test_can_update_link(): void
    {
        $link = Link::create(['short_slug' => 'old-slug', 'target_url' => 'https://old.com']);

        $response = $this->actingAs($this->user)->put("/admin/links/{$link->id}", [
            'short_slug' => 'new-slug',
            'target_url' => 'https://new.com',
        ]);

        $response->assertRedirect('/admin/links');
        $this->assertDatabaseHas('links', [
            'id' => $link->id,
            'short_slug' => 'new-slug',
            'target_url' => 'https://new.com',
        ]);
    }

    public function test_can_delete_link(): void
    {
        $link = Link::create(['short_slug' => 'to-delete', 'target_url' => 'https://example.com']);

        $response = $this->actingAs($this->user)->delete("/admin/links/{$link->id}");

        $response->assertRedirect('/admin/links');
        $this->assertDatabaseMissing('links', ['id' => $link->id]);
    }

    public function test_deleting_link_also_deletes_clicks(): void
    {
        $link = Link::create(['short_slug' => 'with-clicks', 'target_url' => 'https://example.com']);
        Click::create(['slug' => 'with-clicks', 'ip_address' => '127.0.0.1']);
        Click::create(['slug' => 'with-clicks', 'ip_address' => '127.0.0.2']);

        $this->assertDatabaseCount('clicks', 2);

        $this->actingAs($this->user)->delete("/admin/links/{$link->id}");

        $this->assertDatabaseCount('clicks', 0);
    }

    public function test_can_search_links(): void
    {
        Link::create(['short_slug' => 'spotify', 'target_url' => 'https://spotify.com']);
        Link::create(['short_slug' => 'twitter', 'target_url' => 'https://twitter.com']);

        $response = $this->actingAs($this->user)->get('/admin/links?q=spotify');

        $response->assertStatus(200);
        $response->assertSee('spotify');
        $response->assertDontSee('twitter');
    }

    public function test_slug_must_be_alpha_dash(): void
    {
        $response = $this->actingAs($this->user)->post('/admin/links', [
            'short_slug' => 'invalid slug with spaces',
            'target_url' => 'https://example.com',
        ]);

        $response->assertSessionHasErrors('short_slug');
    }

    public function test_target_url_must_be_valid_url(): void
    {
        $response = $this->actingAs($this->user)->post('/admin/links', [
            'short_slug' => 'valid-slug',
            'target_url' => 'not-a-url',
        ]);

        $response->assertSessionHasErrors('target_url');
    }
}
