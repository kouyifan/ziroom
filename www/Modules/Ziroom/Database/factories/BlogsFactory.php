<?php

use Faker\Generator as Faker;

$factory->define(\Modules\Ziroom\Entities\blogs::class, function (Faker $faker) {
    return [
        'title' => $faker->realText(20),
        'content'   =>  $faker->realText(200),
        'author'    =>   $faker->userName,
        'created_at'    =>  $faker->dateTime('now'),
    ];
});
