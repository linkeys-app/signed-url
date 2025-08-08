<?php

namespace Linkeys\UrlSigner\Tests\Integration\Support\LinkRepository;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Linkeys\UrlSigner\Models\Link;
use Linkeys\UrlSigner\Support\LinkRepository\EloquentLinkRepository;
use Linkeys\UrlSigner\Tests\TestCase;

class EloquentLinkRepositoryTest extends TestCase
{

    /**
     * @var EloquentLinkRepository
     */
    private $linkRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->linkRepository = new EloquentLinkRepository(new Link);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_finds_a_link_model_by_uuid(){
        $link = factory(Link::class)->create();
        $repositoryLink = $this->linkRepository->findByUuid($link->uuid);
        $this->assertTrue($link->is($repositoryLink));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_throws_a_model_not_found_exception_when_uuid_not_found(){
        $this->expectException(ModelNotFoundException::class);
        $this->linkRepository->findByUuid('does-not-exist');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_saves_a_model(){
        $link = factory(Link::class)->create();
        $link->url = 'updated_url';
        $this->linkRepository->save($link);

        $this->assertDatabaseHas(config('links.tables.links'), ['url' => 'updated_url']);
    }
    
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_creates_and_returns_model(){

        $attributes = [
            'url' => 'https://example.com',
            'data' => [],
            'click_limit' => 5,
            'expiry' => Carbon::now()->format('Y-m-d H:i:d')
        ];

        $link = $this->linkRepository->create($attributes);

        $this->assertEquals($attributes,
            $link->only(array_keys($attributes))
        );

        $this->assertDatabaseHas(config('links.tables.links'), ['id' => $link->id]);
    }
    
}