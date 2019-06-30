<?php

namespace Linkeys\UrlSigner\Tests\Integration\Support\GroupRepository;

use Carbon\Carbon;
use Linkeys\UrlSigner\Models\Group;
use Linkeys\UrlSigner\Models\Link;
use Linkeys\UrlSigner\Support\GroupRepository\EloquentGroupRepository;
use Linkeys\UrlSigner\Tests\TestCase;

class EloquentGroupRepositoryTest extends TestCase
{

    /**
     * @var EloquentGroupRepository
     */
    private $groupRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->groupRepository = new EloquentGroupRepository(new Group);
    }

    /** @test */
    public function it_creates_and_returns_model(){

        $attributes = [
            'click_limit' => 5,
            'expiry' => Carbon::now()->format('Y-m-d H:i:d')
        ];

        $group = $this->groupRepository->create($attributes);

        $this->assertEquals($attributes,
            $group->only(array_keys($attributes))
        );

        $this->assertDatabaseHas(config('links.tables.groups'), ['id' => $group->id]);
    }

    /** @test */
    public function it_attaches_a_link_to_a_group(){
        $link = factory(Link::class)->create();
        $group = factory(Group::class)->create();

        $this->groupRepository->pushLink($group, $link);

        $this->assertEquals($group->id, $link->refresh()->group_id);

    }
    
}