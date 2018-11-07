<?php

use Faker\Generator as Faker;

$factory->define(Wuwx\LaravelWorkflow\Entities\Transition::class, function (Faker $faker) {
    $workflow = factory(Wuwx\LaravelWorkflow\Entities\Workflow::class)->create(['initial_place' => 'place_1']);
    $place_1 = factory(Wuwx\LaravelWorkflow\Entities\Place::class)->create(['workflow_id' => $workflow->id, 'name' => 'place_1']);
    $place_2 = factory(Wuwx\LaravelWorkflow\Entities\Place::class)->create(['workflow_id' => $workflow->id, 'name' => 'place_2']);
    return [
        'name' => $faker->word,
        'workflow_id' => $workflow->id,
        'froms' => [$place_1->name],
        'tos' => [$place_2->name],
    ];
});
