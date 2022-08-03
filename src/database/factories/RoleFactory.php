<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Permission\Models\Role;

/**
 * Define the model's default state.
 *
 * @var Factory $factory */

$factory->define(Role::class,function (Facker $facker){
    return[
        'name'=>$facker->name
    ];
});
