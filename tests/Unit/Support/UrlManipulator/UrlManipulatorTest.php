<?php


namespace Linkeys\UrlSigner\Tests\Unit\Support\UrlManipulator;

use Linkeys\UrlSigner\Tests\TestCase;
use Linkeys\UrlSigner\Support\UrlManipulator\SpatieUrlManipulator;

class UrlManipulatorTest extends TestCase
{

    protected $url;

    public function setUp(): void
    {
        $this->url = 'https://example.com?foo=bar';
    }

    /**
     * @test
     */
    public function url_manipulator_can_be_instantiated()
    {
        $urlInterface = new SpatieUrlManipulator;
        $this->assertInstanceOf(SpatieUrlManipulator::class, $urlInterface);
    }

    /**
     * @test
     */
    public function url_manipulator_can_accept_and_return_a_url()
    {
        $urlInterface = new SpatieUrlManipulator;
        $urlInterface->setUrl($this->url);

        $this->assertEquals($this->url, $urlInterface->getUrl());
    }

    /**
     * @test
     */
    public function url_manipulator_can_get_the_query_from_a_url()
    {
        $urlInterface = new SpatieUrlManipulator;
        $urlInterface->setUrl($this->url);

        $this->assertEquals([
            'foo' => 'bar'
        ], $urlInterface->getQuery());
    }

    /**
     * @test
     */
    public function url_manipulator_can_append_a_query_to_a_url()
    {
        $urlInterface = new SpatieUrlManipulator;
        $urlInterface->setUrl($this->url);
        $urlInterface->appendQuery([
            'one' => 'two'
        ]);

        $this->assertEquals([
            'foo' => 'bar',
            'one' => 'two'
        ], $urlInterface->getQuery());

    }

    /**
     * @test
     */
    public function url_manipulator_can_return_full_url_with_new_query_string()
    {
        $urlInterface = new SpatieUrlManipulator;
        $urlInterface->setUrl($this->url);
        $urlInterface->appendQuery([
            'one' => 'two'
        ]);

        $this->assertEquals($this->url.'&one=two', $urlInterface->getUrl());

        return $urlInterface;

    }

    /** @test */
    public function url_manipulator_returns_empty_array_when_query_is_requested_with_no_query_string_available(){
        $urlInterface = new SpatieUrlManipulator;
        $urlInterface->setUrl('http://example.com');
        $this->assertEmpty($urlInterface->getQuery());
    }

    /** @test */
    public function it_removes_a_query_parameter(){
        $urlInterface = new SpatieUrlManipulator;
        $urlInterface->setUrl("https://example.com?foo=bar&uuid=xyz123");
        $urlInterface->removeQuery('uuid');
        $this->assertEquals('https://example.com?foo=bar', $urlInterface->getUrl());
    }

    /** @test */
    public function url_manipulator_keeps_the_protocol_intact_after_removing_a_query(){
        $urlInterface = new SpatieUrlManipulator;
        $urlInterface->setUrl('https://example.com?q=123&v=444');
        $urlInterface->removeQuery('q');
        $this->assertEquals('https://example.com?v=444', $urlInterface->getUrl());
    }

}













