<?php

$factory->define(\Linkeys\LinkGenerator\Models\Group::class, function(\Faker\Generator $faker) {
    return [
        'expiry' => null,
        'click_limit' => null
    ];
});