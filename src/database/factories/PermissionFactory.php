<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Permission\Models\Permission;
use Facker\Generator as Facker;

    /**
     * Define the model's default state.
     *
     * @var Factory $factory */

$factory->define(Permission::class,function (Facker $facker){
    return[
        'name'=>$facker->name
    ];
});
