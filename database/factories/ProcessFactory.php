<?php

use Faker\Generator as Faker;

$factory->define(Wuwx\LaravelWorkflow\Entities\Process::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'version_id' => function () {
            return factory(Wuwx\LaravelWorkflow\Entities\Version::class)->create()->id;
        },
    ];
});
