<?php

function create($model, $count = 1, $overrides = [])
{
    if ($count > 1) {
        return factory($model, $count)->create($overrides);
    }

    return factory($model)->create($overrides);
}

function make($model, $count = 1, $overrides = [])
{
    if ($count > 1) {
        return factory($model, $count)->make($overrides);
    }

    return factory($model)->make($overrides);
}
