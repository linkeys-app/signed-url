<?php

namespace Linkeys\UrlSigner\Tests\Integration\Listeners;

use Illuminate\Support\Facades\Event;
use Linkeys\UrlSigner\Events\LinkClicked;
use Linkeys\UrlSigner\Listeners\RecordLinkClick;
use Linkeys\UrlSigner\Models\Link;
use Linkeys\UrlSigner\Tests\TestCase;

class RecordLinkClickTest extends TestCase
{

    /** @test */
    public function it_adds_one_to_the_clicks_column_if_the_clicks_are_initially_zero(){
        $link = factory(Link::class)->create(['clicks' => 0]);

        $recordLinkClickListener = new RecordLinkClick;
        $linkClicked = new LinkClicked($link);
        $recordLinkClickListener->handle($linkClicked);

        $this->assertEquals(1, Link::find($link->id)->clicks);
    }

    /** @test */
    public function it_adds_one_to_the_clicks_column_if_the_clicks_are_initially_non_zero(){
        $link = factory(Link::class)->create(['clicks' => 10]);

        $recordLinkClickListener = new RecordLinkClick;
        $linkClicked = new LinkClicked($link);
        $recordLinkClickListener->handle($linkClicked);

        $this->assertEquals(11,  Link::find($link->id)->clicks);
    }

    /** @test */
    public function it_responds_to_the_fired_event(){
        $link = factory(Link::class)->create(['clicks' => 0]);

        Event::dispatch(new LinkClicked($link));

        $this->assertEquals(1, Link::find($link->id)->clicks);

    }
}