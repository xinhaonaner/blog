<?php

namespace Tests\Unit\Pages\Users;

use Tests\TestCase;
use Tests\CreatesUser;
use Illuminate\Http\Response;
use Tests\InteractsWithDatabase;

class UserIndexPageTest extends TestCase
{
    use InteractsWithDatabase, CreatesUser;

    /** @test */
    public function it_can_refresh_the_user_page()
    {
        // Actions
        $this->createUser()->actingAs($this->user)
            ->visit(route('canvas.admin.user.index'))
            ->click('Refresh Users');

        // Assertions
        $this->assertResponseStatus(Response::HTTP_OK);
        $this->assertSessionMissing('errors');
        $this->seePageIs(route('canvas.admin.user.index'));
    }

    /** @test */
    public function it_can_add_a_user_from_the_user_index_page()
    {
        // Actions
        $this->createUser()->actingAs($this->user)
            ->visit(route('canvas.admin.user.index'))
            ->click('create-user');

        // Assertions
        $this->assertResponseStatus(Response::HTTP_OK);
        $this->assertSessionMissing('errors');
        $this->seePageIs(route('canvas.admin.user.create'));
    }

    /** @test */
    public function it_cannot_access_the_user_index_page_if_user_is_not_an_admin()
    {
        // Actions
        $this->createUser(['role' => 0])->actingAs($this->user)->visit(route('canvas.admin.user.index'));

        // Assertions
        $this->assertResponseStatus(Response::HTTP_OK);
        $this->seePageIs(route('canvas.admin'));
        $this->assertSessionMissing('errors');
    }
}
