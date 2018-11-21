<?php

namespace Tests\Unit\Pages\Tag;

use Tests\TestCase;
use Tests\CreatesUser;
use Tests\InteractsWithDatabase;

class TagIndexPageTest extends TestCase
{
    use InteractsWithDatabase, CreatesUser;

    /** @test */
    public function it_can_refresh_the_tag_page()
    {
        $this->createUser()->actingAs($this->user)
            ->visit(route('canvas.admin.tag.index'))
            ->click('Refresh Tags');
        $this->assertSessionMissing('errors');
        $this->seePageIs(route('canvas.admin.tag.index'));
    }

    /** @test */
    public function it_can_add_a_tag_from_the_tag_index_page()
    {
        $this->createUser()->actingAs($this->user)
            ->visit(route('canvas.admin.tag.index'))
            ->click('create-tag');
        $this->assertSessionMissing('errors');
        $this->seePageIs(route('canvas.admin.tag.create'));
    }
}
