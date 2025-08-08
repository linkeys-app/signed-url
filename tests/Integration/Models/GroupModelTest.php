<?php

namespace Linkeys\GroupGenerator\Tests\Integration\Group;

use Carbon\Carbon;
use Linkeys\UrlSigner\Models\Group;
use Linkeys\UrlSigner\Models\Link;
use Linkeys\UrlSigner\Tests\TestCase;

class GroupModelTest extends TestCase
{

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_uses_the_table_name_from_configuration_files(){
        $this->assertEquals(config('links.tables.groups'), (new Group)->getTable());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function has_click_limit_parameter_when_created()
    {
        $group = factory(Group::class)->create(['click_limit' => 5]);
        $this->assertEquals(5, $group->click_limit);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function has_expiry_parameter_when_created()
    {
        $group = factory(Group::class)->create(['expiry' => Carbon::now()]);
        $this->assertInstanceOf(\DateTime::class, $group->expiry);
    }


    #[\PHPUnit\Framework\Attributes\Test]
    public function it_accepts_the_expiry_when_it_is_null(){

        $group = factory(Group::class)->create();
        $this->assertEquals(null, $group->expiry);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_casts_a_string_expiry_into_a_datetime(){
        $expiry = 'now';

        $group = factory(Group::class)->create(['expiry' => $expiry]);

        $this->assertInstanceOf(\DateTime::class, $group->expiry);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_casts_a_timestamp_into_a_datetime(){
        $expiry = Carbon::now()->timestamp;

        $group = factory(Group::class)->create(['expiry' => $expiry]);

        $this->assertInstanceOf(\DateTime::class, $group->expiry);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_accepts_a_datetime_instance_as_an_expiry(){
        $expiry = Carbon::now();

        $group = factory(Group::class)->create(['expiry' => $expiry]);

        $this->assertInstanceOf(\DateTime::class, $expiry);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function click_limit_reached_returns_false_if_the_sum_of_the_link_clicks_are_less_than_the_click_limit(){
        $group = factory(Group::class)->create(['click_limit' => 5]);
        factory(Link::class)->create(['group_id' => $group->id, 'clicks' => 1]);
        factory(Link::class)->create(['group_id' => $group->id, 'clicks' => 1]);
        factory(Link::class)->create(['group_id' => $group->id, 'clicks' => 1]);
        factory(Link::class)->create(['group_id' => $group->id, 'clicks' => 1]);
        $this->assertFalse($group->clickLimitReached());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function click_limit_reached_returns_true_if_the_sum_of_the_link_clicks_are_equal_to_the_number_of_clicks(){
        $group = factory(Group::class)->create(['click_limit' => 5]);
        factory(Link::class)->create(['group_id' => $group->id, 'clicks' => 2]);
        factory(Link::class)->create(['group_id' => $group->id, 'clicks' => 2]);
        factory(Link::class)->create(['group_id' => $group->id, 'clicks' => 1]);
        $this->assertTrue($group->clickLimitReached());
    }
    #[\PHPUnit\Framework\Attributes\Test]
    public function click_limit_reached_returns_true_if_the_the_sum_of_the_link_clicks_are_greater_than_the_number_of_clicks(){
        $group = factory(Group::class)->create(['click_limit' => 5]);
        factory(Link::class)->create(['group_id' => $group->id, 'clicks' => 1]);
        factory(Link::class)->create(['group_id' => $group->id, 'clicks' => 1]);
        factory(Link::class)->create(['group_id' => $group->id, 'clicks' => 4]);
        factory(Link::class)->create(['group_id' => $group->id, 'clicks' => 5]);
        $this->assertTrue($group->clickLimitReached());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function click_limit_reached_returns_false_if_the_click_limit_is_null(){
        $group = factory(Group::class)->create(['click_limit' => null]);
        factory(Link::class)->create(['group_id' => $group->id, 'clicks' => 1]);
        factory(Link::class)->create(['group_id' => $group->id, 'clicks' => 1]);
        factory(Link::class)->create(['group_id' => $group->id, 'clicks' => 4]);
        factory(Link::class)->create(['group_id' => $group->id, 'clicks' => 5]);
        $this->assertFalse($group->clickLimitReached());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function expired_is_false_if_group_expiry_is_null(){
        $group = factory(Group::class)->create(['expiry' => null]);
        $this->assertFalse($group->expired());
    }
    #[\PHPUnit\Framework\Attributes\Test]
    public function expired_is_true_if_the_group_expiry_date_is_in_the_past(){
        $group = factory(Group::class)->create(['expiry' => Carbon::now()->subMinutes(10)]);
        $this->assertTrue($group->expired());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function expired_is_false_if_group_expiry_date_in_the_future(){
        $group = factory(Group::class)->create(['expiry' => Carbon::now()->addMinutes(10)]);
        $this->assertFalse($group->expired());
    }

}