<?php

namespace Linkeys\UrlSigner\Tests;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Linkeys\UrlSigner\Models\Group;
use Linkeys\UrlSigner\Models\Link;
use Linkeys\UrlSigner\Providers\UrlSignerServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{

    use DatabaseTransactions;

    /**
     * @var Factory
     */
    protected $factory;

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('links.tables.links', 'links');
        $app['config']->set('links.tables.groups', 'groups');
        $app['config']->set('links.query_key', 'uuid');
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);


    }

    public function setUp(): void
    {
        parent::setUp();
        $this->loadMigrationsFrom(realpath(__DIR__ . '/../database/migrations'));
        $this->withFactories(__DIR__ . '/../database/factories');
    }


    protected function getPackageProviders($app)
    {
        return [
            UrlSignerServiceProvider::class,
        ];
    }

}