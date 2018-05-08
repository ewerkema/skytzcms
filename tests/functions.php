<?php

function create($class, $attributes = []) {
    return factory($class)->create($attributes);
}

function make($class, $attributes = []) {
    return factory($class)->make($attributes);
}

function createTimes($class, $amount) {
    return factory($class, $amount)->create();
}

function createWithUserAttributes($model, $attributes = [], $userAttributes = []) {
    factory($model)->create($attributes . [
            'user_id' => factory('App\User')->create($userAttributes)->id
        ]);
}