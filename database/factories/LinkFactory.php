<?php

$factory->define(\Linkeys\LinkGenerator\Models\Link::class, function(\Faker\Generator $faker) {
    return [
        'url' => $faker->url,
        'data' => [
            $faker->word => $faker->word,
            $faker->word => $faker->word,
        ],
        'expiry' => null,
        'click_limit' => null,
        'clicks' => 0,
        'uuid' => $faker->uuid
    ];
});