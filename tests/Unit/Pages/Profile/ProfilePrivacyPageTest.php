<?php

namespace Tests\Unit\Pages\Profile;

use Tests\TestCase;
use Tests\TestHelper;
use Tests\CreatesUser;
use Illuminate\Http\Response;
use Tests\InteractsWithDatabase;
use Illuminate\Support\Facades\Auth;

class ProfilePrivacyPageTest extends TestCase
{
    use InteractsWithDatabase, CreatesUser, TestHelper;

    /** @test */
    public function it_can_refresh_the_profile_privacy_page()
    {
        // Actions
        $this->createUser()->actingAs($this->user)
            ->visit(route('canvas.admin.profile.privacy'))
            ->click('Refresh Profile');

        // Assertions
        $this->assertResponseStatus(Response::HTTP_OK)
            ->seePageIs(route('canvas.admin.profile.index'))
            ->see(e('Profile'));
        $this->assertSessionMissing('errors');
    }

    /** @test */
    public function it_validates_the_current_password()
    {
        // Actions
        $this->createUser()->actingAs($this->user)
            ->visit(route('canvas.admin.profile.privacy'))
            ->type('wrongPassword', 'password')
            ->type('newPassword', 'new_password')
            ->type('newPassword', 'new_password_confirmation')
            ->press('Save');

        // Assertions
        $this->assertResponseStatus(Response::HTTP_OK)
            ->seePageIs(route('canvas.admin.profile.privacy'))
            ->see(e('These credentials do not match our records.'));
    }

    /** @test */
    public function it_can_update_the_password()
    {
        // Actions
        $this->createUser()->actingAs($this->user)
            ->visit(route('canvas.admin.profile.privacy'))
            ->type('password', 'password')
            ->type('newPassword', 'new_password')
            ->type('newPassword', 'new_password_confirmation')
            ->press('Save');

        // Assertions
        $this->assertResponseStatus(Response::HTTP_OK)
            ->seePageIs(route('canvas.admin.profile.privacy'))
            ->see(e('Success! Your password has been updated.'))
            ->assertSessionMissing('errors');
        $this->assertTrue(Auth::validate([
            'email'    => $this->user->email,
            'password' => 'newPassword',
        ]));
    }
}
