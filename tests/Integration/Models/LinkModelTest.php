<?php

namespace Linkeys\UrlSigner\Tests\Integration\Link;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Linkeys\UrlSigner\Contracts\Models\Link as LinkContract;
use Linkeys\UrlSigner\Models\Link;
use Linkeys\UrlSigner\Support\UrlManipulator\UrlManipulator;
use Linkeys\UrlSigner\Tests\TestCase;

class LinkModelTest extends TestCase
{

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_uses_the_table_name_from_configuration_files(){
        $this->assertEquals(config('links.tables.links'), (new Link)->getTable());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_has_a_belong_to_relationship_with_group(){
        $this->assertInstanceOf(BelongsTo::class, (new Link)->group());
    }
    #[\PHPUnit\Framework\Attributes\Test]
    public function has_url_parameter_when_created()
    {
        $link = factory(Link::class)->create(['url' => 'https://example.com']);
        $this->assertEquals('https://example.com', $link->url);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function has_data_parameter_when_created()
    {
        $link = factory(Link::class)->create(['data' => ['foo' => 'bar']]);
        $this->assertEquals(['foo' => 'bar'], $link->data);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function has_click_limit_parameter_when_created()
    {
        $link = factory(Link::class)->create(['click_limit' => 5]);
        $this->assertEquals(5, $link->click_limit);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function has_expiry_parameter_when_created()
    {
        $link = factory(Link::class)->create(['expiry' => Carbon::now()]);
        $this->assertInstanceOf(\DateTime::class, $link->expiry);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function generated_a_uuid()
    {
        $link = factory(Link::class)->create();
        $this->assertIsString($link->uuid);
        $this->assertEquals(36, strlen($link->uuid));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function uuid_can_be_overridden(){
        $link = factory(Link::class)->create(['uuid' => 'testuuid']);
        $this->assertEquals('testuuid', $link->uuid);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_accepts_the_expiry_when_it_is_null(){

        $link = factory(Link::class)->create(['expiry' => null]);
        $this->assertEquals(null, $link->expiry);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_casts_a_string_expiry_into_a_datetime(){
        $expiry = 'now';

        $link = factory(Link::class)->create(['expiry' => $expiry]);

        $this->assertInstanceOf(\DateTime::class, $link->expiry);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_casts_a_timestamp_into_a_datetime(){
        $expiry = Carbon::now()->timestamp;

        $link = factory(Link::class)->create(['expiry' => $expiry]);

        $this->assertInstanceOf(\DateTime::class, $link->expiry);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_accepts_a_datetime_instance_as_an_expiry(){
        $expiry = Carbon::now();

        $link = factory(Link::class)->create(['expiry' => $expiry]);

        $this->assertInstanceOf(\DateTime::class, $expiry);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_appends_the_query_key_to_the_full_url(){
        $link = factory(Link::class)->create();
        $this->assertStringContainsString('?uuid=', $link->getFullUrl());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_appends_the_uuid_to_the_url(){
        $link = factory(Link::class)->create();
        $this->assertStringContainsString($link->uuid, $link->getFullUrl());
    }


    #[\PHPUnit\Framework\Attributes\Test]
    public function it_sets_a_url_and_query_string_then_returns_the_full_url_from_the_url_manipulator(){
        // Given the Url Manipulator is mocked
        $urlManipulator = $this->prophesize(UrlManipulator::class);
        $this->instance(UrlManipulator::class, $urlManipulator->reveal());

        // When a link is created and the full url retrieved
        $link = factory(Link::class)->create();
        $link->getFullUrl();

        // Then we expect
        $urlManipulator->setUrl($link->url)->shouldHaveBeenCalled();
        $urlManipulator->appendQuery(['uuid' => $link->uuid])->shouldHaveBeenCalled();
        $urlManipulator->getUrl()->shouldHaveBeenCalled();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function click_limit_reached_returns_false_if_the_click_limit_is_not_reached(){
        $link = factory(Link::class)->create(['click_limit' => 5, 'clicks' => 0]);
        $this->assertFalse($link->clickLimitReached());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function click_limit_reached_returns_true_if_the_click_limit_is_equal_to_the_number_of_clicks(){
        $link = factory(Link::class)->create(['click_limit' => 5, 'clicks' => 5]);
        $this->assertTrue($link->clickLimitReached());
    }
    #[\PHPUnit\Framework\Attributes\Test]
    public function click_limit_reached_returns_true_if_the_click_limit_is_reached(){
        $link = factory(Link::class)->create(['click_limit' => 5, 'clicks' => 10]);
        $this->assertTrue($link->clickLimitReached());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function click_limit_reached_returns_false_if_the_click_limit_is_null(){
        $link = factory(Link::class)->create(['click_limit' => null]);
        $this->assertFalse($link->clickLimitReached());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function expired_is_false_if_expiry_is_null(){
        $link = factory(Link::class)->create(['expiry' => null]);
        $this->assertFalse($link->expired());
    }
    #[\PHPUnit\Framework\Attributes\Test]
    public function expired_is_true_if_the_date_is_in_the_past(){
        $link = factory(Link::class)->create(['expiry' => Carbon::now()->subMinutes(10)]);
        $this->assertTrue($link->expired());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function expired_is_false_if_date_in_the_future(){
        $link = factory(Link::class)->create(['expiry' => Carbon::now()->addMinutes(10)]);
        $this->assertFalse($link->expired());
    }
}