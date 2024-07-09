<?php

namespace App\Http\Controllers\Manage\Marketing;

use App\Http\Controllers\Manage\Controller;
use Illuminate\Http\Request;

class GraphController extends Controller
{
    //
    public function inventory()
    {
        return view('manage.marketing.graph.inventory');
    }

    public function reservation()
    {
        return view('manage.marketing.graph.reservation');
    }
}
