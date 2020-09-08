<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator;
use Spatie\Multitenancy\Models\Tenant;

$factory->define(Tenant::class, function (Generator $faker) {
    return [
        'name' => $faker->name,
        'domain' => $faker->unique()->domainName,
        'database' => $faker->userName,
    ];
});
