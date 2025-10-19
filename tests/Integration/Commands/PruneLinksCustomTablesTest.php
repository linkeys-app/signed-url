<?php

namespace Linkeys\UrlSigner\Tests\Integration\Commands;

use Linkeys\UrlSigner\Models\Group;
use Linkeys\UrlSigner\Models\Link;
use Linkeys\UrlSigner\Tests\TestCase;

class PruneLinksCustomTablesTest extends TestCase
{

    public function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        $app['config']->set('links.tables.links', 'custom_table_links');
        $app['config']->set('links.tables.groups', 'custom_table_groups');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_removes_expired_links() {
        $link1 = factory(Link::class)->create(['expiry' => null]);
        $link2 = factory(Link::class)->create(['expiry' => now()->addDay()]);
        $link3 = factory(Link::class)->create(['expiry' => now()->addMinute()]);
        $link4 = factory(Link::class)->create(['expiry' => now()->subMinute()]);
        $link5 = factory(Link::class)->create(['expiry' => now()->subDay()]);
        $link6 = factory(Link::class)->create(['expiry' => now()->subYear()]);

        $this->assertDatabaseCount('custom_table_links', 6);

        $this->artisan('signed-url:prune --expired')
            ->expectsOutput('Pruning 3 expired links')
            ->assertExitCode(0);

        $this->assertDatabaseCount('custom_table_links', 3);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_removes_used_links() {
        $link1 = factory(Link::class)->create(['clicks' => 0]);
        $link2 = factory(Link::class)->create(['clicks' => 0]);
        $link3 = factory(Link::class)->create(['clicks' => 2]);
        $link4 = factory(Link::class)->create(['clicks' => 3]);
        $link5 = factory(Link::class)->create(['clicks' => 4]);
        $link6 = factory(Link::class)->create(['clicks' => 5]);

        $this->assertDatabaseCount('custom_table_links', 6);

        $this->artisan('signed-url:prune --used')
            ->expectsOutput('Pruning 4 used links')
            ->assertExitCode(0);

        $this->assertDatabaseCount('custom_table_links', 2);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_does_not_delete_any_partially_used_links_with_clicks_remaining() {
        $link1 = factory(Link::class)->create(['clicks' => 2, 'click_limit' => null]);
        $link1 = factory(Link::class)->create(['clicks' => 2, 'click_limit' => 1]);
        $link2 = factory(Link::class)->create(['clicks' => 2, 'click_limit' => 2]);
        $link2 = factory(Link::class)->create(['clicks' => 2, 'click_limit' => 3]);

        $this->assertDatabaseCount('custom_table_links', 4);

        $this->artisan('signed-url:prune --used-fully')
            ->expectsOutput('Pruning 3 used links')
            ->assertExitCode(0);

        $this->assertDatabaseCount('custom_table_links', 1);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_removes_links_where_one_in_the_group_has_been_used() {
        $group1 = factory(Group::class)->create();
        $link1 = factory(Link::class)->create(['group_id' => $group1->id, 'clicks' => 0]);
        $link2 = factory(Link::class)->create(['group_id' => $group1->id, 'clicks' => 0]);
        $link3 = factory(Link::class)->create(['group_id' => $group1->id, 'clicks' => 0]);

        $group2 = factory(Group::class)->create();
        $link4 = factory(Link::class)->create(['group_id' => $group2->id, 'clicks' => 0]);
        $link5 = factory(Link::class)->create(['group_id' => $group2->id, 'clicks' => 1]);
        $link6 = factory(Link::class)->create(['group_id' => $group2->id, 'clicks' => 2]);

        $this->assertDatabaseCount('custom_table_links', 6);
        $this->assertDatabaseCount('custom_table_groups', 2);

        $this->artisan('signed-url:prune --used')
            ->expectsOutput('Pruning 1 used groups')
            ->expectsOutput('Pruning 3 used links')
            ->assertExitCode(0);

        $this->assertDatabaseCount('custom_table_groups', 1);
        $this->assertDatabaseCount('custom_table_links', 3);

    }

}