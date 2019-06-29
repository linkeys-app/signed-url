<?php

namespace Linkeys\LinkGenerator\Tests\Integration;

use Carbon\Carbon;
use Linkeys\LinkGenerator\LinkGenerator;
use Linkeys\LinkGenerator\Models\Group;
use Linkeys\LinkGenerator\Models\Link;
use Linkeys\LinkGenerator\Support\GroupRepository\EloquentGroupRepository;
use Linkeys\LinkGenerator\Support\LinkRepository\EloquentLinkRepository;
use Linkeys\LinkGenerator\Support\UrlManipulator\SpatieUrlManipulator;
use Linkeys\LinkGenerator\Tests\TestCase;

class LinkGeneratorTest extends TestCase
{

    /** @var LinkGenerator */
    public $links;

    public function setUp(): void
    {
        parent::setUp();

        $this->links = new LinkGenerator(
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
        $group = $this->links->group(function (LinkGenerator $linkGenerator) {
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
        $group = $this->links->group(function(LinkGenerator $link) {
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