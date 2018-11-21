<?php

namespace Tests\Unit\Pages\Home;

use Tests\TestCase;
use Tests\CreatesUser;
use Illuminate\Http\Response;
use Tests\InteractsWithDatabase;

class HomeIndexPageTest extends TestCase
{
    use InteractsWithDatabase, CreatesUser;

    /** @test */
    public function it_can_preview_the_blog_from_the_home_page()
    {
        // Actions
        $this->createUser()->actingAs($this->user)
            ->visit(route('canvas.admin'))
            ->click('View Site');

        // Assertions
        $this->assertResponseStatus(Response::HTTP_OK)
            ->seePageIs(route('canvas.home'))
            ->see(e(env('APP_NAME')));
        $this->assertSessionMissing('errors');
    }

    /** @test */
    public function it_displays_all_cards_if_user_is_an_admin()
    {
        // Actions
        $this->createUser()->actingAs($this->user)
            ->visit(route('canvas.admin'));

        // Assertions
        $this->assertResponseStatus(Response::HTTP_OK)
            ->seePageIs(route('canvas.admin'))
            ->see(e('Welcome to Canvas!'))
            ->see(e('At a Glance'));
        $this->assertSessionMissing('errors');
    }

    /** @test */
    public function it_does_not_display_all_cards_if_user_is_not_an_admin()
    {
        // Actions
        $this->createUser(['role' => 0])->actingAs($this->user)
            ->visit(route('canvas.admin'));

        // Assertions
        $this->assertResponseStatus(Response::HTTP_OK)
            ->seePageIs(route('canvas.admin'))
            ->dontSee('Welcome to Canvas!')
            ->dontSee('At a Glance');
        $this->assertSessionMissing('errors');
    }
}
