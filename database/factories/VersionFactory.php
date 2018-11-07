<?php

use Faker\Generator as Faker;

$factory->define(Wuwx\LaravelWorkflow\Entities\Version::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'workflow_id' => function () {
            return factory(Wuwx\LaravelWorkflow\Entities\Workflow::class)->create()->id;
        },
    ];
});
