<?php

use Faker\Generator as Faker;

$factory->define(App\Blog::class, function (Faker $faker) {
    return [
//        'title' => $faker->words(),
        'content'   =>  $faker->realText(200),
        'author'    =>   $faker->userName,
        'updated_at'    =>  $faker->dateTime()
    ];
});
