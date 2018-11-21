<?php

namespace Tests\Unit\Pages\Tools;

use Tests\TestCase;
use Tests\CreatesUser;
use Illuminate\Http\Response;
use Tests\InteractsWithDatabase;

class ToolsIndexPageTest extends TestCase
{
    use InteractsWithDatabase, CreatesUser;

    /**
     * Get the successful maintenance mode activated message.
     *
     * @return string
     */
    protected function getMaintenanceModeActiveMessage()
    {
        return trans('canvas::messages.enable_maintenance_mode_success');
    }

    /**
     * Get the successful maintenance mode deactivated message.
     *
     * @return string
     */
    protected function getMaintenanceModeInactiveMessage()
    {
        return trans('canvas::messages.disable_maintenance_mode_success');
    }

    /** @test */
    public function it_can_refresh_the_tools_page()
    {
        // Actions
        $this->createUser()->actingAs($this->user)
            ->visit(route('canvas.admin.tools'))
            ->click('Refresh Tools');

        // Assertions
        $this->assertResponseStatus(Response::HTTP_OK)
            ->seePageIs(route('canvas.admin.tools'))
            ->see(e('Tools'));
        $this->assertSessionMissing('errors');
    }

    /** @test */
    public function it_can_clear_the_application_cache()
    {
        // Actions
        $this->createUser()->actingAs($this->user)
            ->visit(route('canvas.admin.tools'))
            ->click('Clear Cache');

        // Assertions
        $this->assertResponseStatus(Response::HTTP_OK)
            ->seePageIs(route('canvas.admin.tools'))
            ->see(e('Tools'));
        $this->assertSessionMissing('errors');
    }

    /** @test */
    public function it_can_enable_maintenance_mode()
    {
        // Actions
        $this->createUser()->actingAs($this->user)
            ->visit(route('canvas.admin.tools'))
            ->press('maintenance_mode');

        // Assertions
        $this->assertResponseStatus(Response::HTTP_OK)
            ->seePageIs(route('canvas.admin.tools'))
            ->see(e('Tools'))
            ->see(trans(self::getMaintenanceModeActiveMessage()));
        $this->assertSessionMissing('errors');
    }

    /** @test */
    public function it_displays_the_503_maintenance_mode_page_while_app_is_down()
    {
        // Actions
        $this->call('GET', route('canvas.home'));

        // Assertions
        $this->assertResponseStatus(Response::HTTP_SERVICE_UNAVAILABLE);
    }

    /** @test */
    public function it_can_disable_maintenance_mode()
    {
        // Actions
        $this->createUser()->actingAs($this->user)
            ->visit(route('canvas.admin.tools'))
            ->press('maintenance_mode');

        // Assertions
        $this->assertResponseStatus(Response::HTTP_OK)
            ->seePageIs(route('canvas.admin.tools'))
            ->see(e('Tools'))
            ->see(trans(self::getMaintenanceModeInactiveMessage()));
        $this->assertSessionMissing('errors');
    }

    /** @test */
    public function it_removes_the_503_maintenance_mode_page_while_app_is_up()
    {
        // Actions
        $this->call('GET', route('canvas.home'));

        // Assertions
        $this->assertResponseStatus(Response::HTTP_OK);
    }

    /** @test */
    public function it_can_download_the_archive_data()
    {
        // Actions
        $this->createUser()->actingAs($this->user)
            ->visit(route('canvas.admin.tools'));

        // Assertions
        $this->assertResponseStatus(Response::HTTP_OK)
            ->seePageIs(route('canvas.admin.tools'))
            ->see(e('Tools'))
            ->see('Download Archive');
        $this->assertSessionMissing('errors');
    }

    /** @test */
    public function it_cannot_access_the_tools_page_if_user_is_not_an_admin()
    {
        // Actions
        $this->createUser(['role' => 0])->actingAs($this->user)
            ->visit(route('canvas.admin.tools'));

        // Assertions
        $this->assertResponseStatus(Response::HTTP_OK)
            ->seePageIs(route('canvas.admin'))
            ->see($this->user->display_name);
        $this->assertSessionMissing('errors');
    }
}
