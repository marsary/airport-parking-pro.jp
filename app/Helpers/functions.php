<?php

use Carbon\Carbon;
use Illuminate\Support\Collection;

function isBlank($value)
{
    if($value === null || trim($value) === '') {
        return true;
    }
    return false;
}

function roundTax($tax):int
{
    return (int) floor($tax);
}



function getKeyMapCollection(Collection $collect, $key = "id")
{
    return  $collect->mapWithKeys(function ($item) use($key) {
        return [$item->{$key} => $item];
    })->all();
}
