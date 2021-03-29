<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ElectroController extends Controller
{
    public function GetItem($item)
    {
        $itemPrecio = \App\ADMITEMPRECIOELE::where('item','=',$item)->get();
        
        $itemSerie = \App\ADMITEMSERIEELE::where('ITEM','=',$item)
        ->where('VENDIDO','=','N')
        ->get();

        $datosMotor = \App\ADMDATOSMOTORELE::where('ITEM','=',$item)->get();

        return response()->json(['precios'=>$itemPrecio,'series'=>$itemSerie,'motor'=>$datosMotor]);
    }

    public function GetItemLiquidacion($item)
    {
        $itemLiq = \App\ADMITEMLIQELE::where('ESTADO','=','A')
        ->where('CANTIDAD','>',0)
        ->get();

        return response()->json($itemLiq);
    }

}
