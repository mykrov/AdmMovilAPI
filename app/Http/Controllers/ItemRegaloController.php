<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ItemRegaloController extends Controller
{
    public function ItemRegalo($item)
    {
        $item = DB::table('ADMITEMREGALOELE')->where('ADMITEMREGALOELE.item','=',$item)
        ->join('ADMITEM','ADMITEMREGALOELE.itemr','=','ADMITEM.ITEM')        
        ->select('ADMITEMREGALOELE.item','ADMITEMREGALOELE.itemr','ADMITEM.nombre',
        'ADMITEM.precio1','ADMITEMREGALOELE.cantidad')
        ->get();

        return response()->json($item);
    }

    public function TodosItemsRegalo()
    {
        $items = DB::table('ADMITEMREGALOELE')
        ->join('ADMITEM','ADMITEMREGALOELE.itemr','=','ADMITEM.ITEM')        
        ->select('ADMITEMREGALOELE.item','ADMITEMREGALOELE.itemr','ADMITEM.nombre',
        'ADMITEM.precio1','ADMITEMREGALOELE.cantidad')
        ->get();

        return response()->json($items);
    }
}