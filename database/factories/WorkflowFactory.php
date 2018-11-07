<?php

use Faker\Generator as Faker;

$factory->define(Wuwx\LaravelWorkflow\Entities\Workflow::class, function (Faker $faker) {
    return [
        'name' => 'issues',
    ];
});
