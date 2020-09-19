<?php

namespace Database\Factories;

$factory->define(\Linkeys\UrlSigner\Models\Group::class, function(\Faker\Generator $faker) {
    return [
        'expiry' => null,
        'click_limit' => null
    ];
});
