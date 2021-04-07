<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ItemRegaloController extends Controller
{
    public function ItemRegalo($item)
    {
        $item = DB::table('ADMITEMREGALOELE')
        ->where('item','=',$item)
        ->get();

        return response()->json($item);
    }
}