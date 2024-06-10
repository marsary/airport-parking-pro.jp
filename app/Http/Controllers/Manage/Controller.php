<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Manage\Commons\HeaderInfo;

abstract class Controller
{
    use HeaderInfo;

    function __construct()
    {
        $this->shareHeaderInfo();
    }
}
