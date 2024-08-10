<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Manage\Controller;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    //
    public function inventories()
    {
        return view('manage.ledger.inventories');
    }
}
