<?php

use App\Models\Office;
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


/**
 * 日付データのフォーマット
 *
 * @param Carbon|string|null $date
 * @return string
 */
function formatDate($date, $format = 'Y-m-d')
{
    if(is_string($date)) {
        $date = Carbon::parse($date);
    }
    return $date ? $date->format($format): '';
}

/**
 * DBの日時最大値を返す
 *
 * @return Carbon
 */
function maxDateTime()
{
    return Carbon::create(9999, 12, 31, 23, 59, 59);
}

function getKeyMapCollection(Collection $collect, $key = "id")
{
    return  $collect->mapWithKeys(function ($item) use($key) {
        return [$item->{$key} => $item];
    })->all();
}



function zeroPadding($number, $digit):string
{
    return str_pad($number, $digit, '0', STR_PAD_LEFT);
}

/**
 * 所属事務所を返す
 *
 * @return Office
 */
function myOffice():Office
{
    return Office::findOrFail(config('const.commons.office_id'));
}
