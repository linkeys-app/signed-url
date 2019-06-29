<?php

namespace Linkeys\LinkGenerator\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Linkeys\LinkGenerator\Contracts\LinkGenerator as LinkGeneratorContract;
use Linkeys\LinkGenerator\Contracts\Models\Group as GroupContract;
use Linkeys\LinkGenerator\Contracts\Models\Link as LinkContract;
use Linkeys\LinkGenerator\Events\LinkClicked;
use Linkeys\LinkGenerator\LinkGenerator;
use Linkeys\LinkGenerator\Listeners\RecordLinkClick;
use Linkeys\LinkGenerator\Models\Group;
use Linkeys\LinkGenerator\Models\Link;
use Linkeys\LinkGenerator\Support\ExpiryNormaliser\NormaliserManager;
use Linkeys\LinkGenerator\Support\ExpiryNormaliser\NormaliserManagerContract;
use Linkeys\LinkGenerator\Support\ExpiryNormaliser\Normalisers\FromDateTime;
use Linkeys\LinkGenerator\Support\ExpiryNormaliser\Normalisers\FromInteger;
use Linkeys\LinkGenerator\Support\ExpiryNormaliser\Normalisers\FromString;
use Linkeys\LinkGenerator\Support\UrlManipulator\SpatieUrlManipulator;
use Linkeys\LinkGenerator\Support\UrlManipulator\UrlManipulator;
use Linkeys\LinkGenerator\Support\Uuid\RamseyUuidCreator;
use Linkeys\LinkGenerator\Support\Uuid\UuidCreator;

class LinkGeneratorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/links.php', config_path('links.php'));
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        Event::listen(LinkClicked::class, RecordLinkClick::class);

    }
    public function register()
    {
        $this->app->bind(LinkGeneratorContract::class, LinkGenerator::class);
        $this->app->bind(LinkContract::class, Link::class);
        $this->app->bind(GroupContract::class, Group::class);
        $this->app->bind(UrlManipulator::class, SpatieUrlManipulator::class);
        $this->app->bind(UuidCreator::class, RamseyUuidCreator::class);

        $normaliserManager = new NormaliserManager();
        $normaliserManager->pushNormaliser(new FromDateTime);
        $normaliserManager->pushNormaliser(new FromString);
        $normaliserManager->pushNormaliser(new FromInteger);
        $this->app->instance(NormaliserManagerContract::class, $normaliserManager);


    }

}