<?php

use Faker\Generator as Faker;

$factory->define(Wuwx\LaravelWorkflow\Entities\Notification::class, function (Faker $faker) {
    return [
        'name' => 'n1',
        'entity_id' => 1,
        'entity_type' => Wuwx\LaravelWorkflow\Entities\Transitions::class,
    ];
});
