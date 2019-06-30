<?php

namespace Linkeys\UrlSigner\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Linkeys\UrlSigner\Contracts\UrlSigner as UrlSignerContract;
use Linkeys\UrlSigner\Contracts\Models\Group as GroupContract;
use Linkeys\UrlSigner\Contracts\Models\Link as LinkContract;
use Linkeys\UrlSigner\Events\LinkClicked;
use Linkeys\UrlSigner\UrlSigner;
use Linkeys\UrlSigner\Listeners\RecordLinkClick;
use Linkeys\UrlSigner\Models\Group;
use Linkeys\UrlSigner\Models\Link;
use Linkeys\UrlSigner\Support\ExpiryNormaliser\NormaliserManager;
use Linkeys\UrlSigner\Support\ExpiryNormaliser\NormaliserManagerContract;
use Linkeys\UrlSigner\Support\ExpiryNormaliser\Normalisers\FromDateTime;
use Linkeys\UrlSigner\Support\ExpiryNormaliser\Normalisers\FromInteger;
use Linkeys\UrlSigner\Support\ExpiryNormaliser\Normalisers\FromString;
use Linkeys\UrlSigner\Support\UrlManipulator\SpatieUrlManipulator;
use Linkeys\UrlSigner\Support\UrlManipulator\UrlManipulator;
use Linkeys\UrlSigner\Support\Uuid\RamseyUuidCreator;
use Linkeys\UrlSigner\Support\Uuid\UuidCreator;

class UrlSignerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/links.php', config_path('links.php'));
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        Event::listen(LinkClicked::class, RecordLinkClick::class);

    }
    public function register()
    {
        $this->app->bind(UrlSignerContract::class, UrlSigner::class);
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