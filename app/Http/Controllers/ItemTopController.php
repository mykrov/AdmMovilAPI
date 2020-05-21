<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemTopController extends Controller
{
    public function GetItemTop(){
        $items = \App\ADMITEMTOP::get();
        return response()->json($items);
    }
}
