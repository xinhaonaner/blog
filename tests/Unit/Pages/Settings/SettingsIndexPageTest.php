<?php

namespace Tests\Unit\Pages\Settings;

use Tests\TestCase;
use Tests\CreatesUser;
use Illuminate\Http\Response;
use Tests\InteractsWithDatabase;

class SettingsIndexPageTest extends TestCase
{
    use InteractsWithDatabase, CreatesUser;

    protected $optionalFields = [
        'blog_description' => '<dt>Description</dt>',
        'blog_seo' => '<dt>Blog SEO</dt>',
        'blog_author' => '<dt>Blog Author</dt>',
        'disqus_name' => '<dt>Disqus</dt>',
        'ga_id' => '<dt>Google Analytics</dt>',
    ];

    protected $requiredFields = [
        'blog_title',
        'blog_subtitle',
    ];

    /** @test */
    public function it_shows_error_messages_for_required_fields()
    {
        // Actions
        $this->createUser()->actingAs($this->user)->visit(route('canvas.admin.settings'));
        foreach ($this->requiredFields as $name) {
            $this->type('', $name);
        }
        $this->press('Save');

        // Assertions
        foreach ($this->requiredFields as $name) {
            $this->see('The '.str_replace('_', ' ', $name).' field is required.');
        }
        $this->assertResponseStatus(Response::HTTP_OK)
            ->seePageIs(route('canvas.admin.settings'))
            ->see(e('Settings'));
        $this->assertSessionMissing('errors');
    }

    /** @test */
    public function it_can_update_the_settings()
    {
        // Actions
        $this->createUser()->actingAs($this->user)->visit(route('canvas.admin.settings'))
            ->type('New and Updated Title', 'blog_title')
            ->press('Save');

        // Assertions
        $this->assertResponseStatus(Response::HTTP_OK)
            ->seePageIs(route('canvas.admin.settings'))
            ->see(e('New and Updated Title'));
        $this->assertSessionMissing('errors');
    }

    /** @test */
    public function it_cannot_access_the_settings_page_if_user_is_not_an_admin()
    {
        // Actions
        $this->createUser(['role' => 0])->actingAs($this->user)
            ->visit(route('canvas.admin.settings'));

        // Assertions
        $this->assertResponseStatus(Response::HTTP_OK)
            ->seePageIs(route('canvas.admin'))
            ->see($this->user->display_name);
        $this->assertSessionMissing('errors');
    }
}
