<?php

namespace Linkeys\UrlSigner\Tests\Integration;

use Carbon\Carbon;
use Linkeys\UrlSigner\UrlSigner;
use Linkeys\UrlSigner\Models\Group;
use Linkeys\UrlSigner\Models\Link;
use Linkeys\UrlSigner\Support\GroupRepository\EloquentGroupRepository;
use Linkeys\UrlSigner\Support\LinkRepository\EloquentLinkRepository;
use Linkeys\UrlSigner\Support\UrlManipulator\SpatieUrlManipulator;
use Linkeys\UrlSigner\Tests\TestCase;

class LinkGeneratorTest extends TestCase
{

    /** @var UrlSigner */
    public $links;

    public function setUp(): void
    {
        parent::setUp();

        $this->links = new UrlSigner(
            new EloquentLinkRepository(new Link),
            new EloquentGroupRepository(new Group),
            new SpatieUrlManipulator
        );
    }

    /** @test */
    public function link_can_be_generated()
    {
        $url = 'https://example.com';

        $link = $this->links->generate($url);
        $this->assertStringContainsString($url, $link->url);
    }

    /** @test */
    public function links_are_stored_in_the_database()
    {
        $link = $this->links->generate('https://example.com');

        $this->assertDatabaseHas(config('links.tables.links'), ['id' => $link->id]);
    }

    /** @test */
    public function sign_can_be_used_as_an_alias(){
        $link = $this->links->sign('https://example.com');

        $this->assertDatabaseHas(config('links.tables.links'), ['id' => $link->id]);

    }

    /** @test */
    public function group_is_created_in_database()
    {
        $group = $this->links->group(function () {
            return;
        });

        $this->assertDatabaseHas(config('links.tables.groups'), $group->toArray());
    }

    /** @test */
    public function links_can_be_created_in_the_callback()
    {
        $group = $this->links->group(function (UrlSigner $linkGenerator) {
            $linkGenerator->generate('https://example.com', [], Carbon::now(), 5);
        });

        $this->assertDatabaseHas(config('links.tables.groups'), $group->toArray());
        $this->assertDatabaseHas(config('links.tables.links'), [
            'url' => 'https://example.com',
            'click_limit' => '5'
        ]);
    }

    /** @test */
    public function it_creates_links_tied_to_groups()
    {
        $group = $this->links->group(function(UrlSigner $link) {
            $link->generate('https://example.com');
            $link->generate('https://example2.com');
        });

        $this->assertDatabaseHas(config('links.tables.groups'), $group->toArray());
        $this->assertDatabaseHas(config('links.tables.links'), [
            'url' => 'https://example.com',
            'group_id' => $group->id
        ]);
        $this->assertDatabaseHas(config('links.tables.links'), [
            'url' => 'https://example2.com',
            'group_id' => $group->id
        ]);
    }

}