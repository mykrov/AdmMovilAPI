<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ElectroController extends Controller
{
    public function GetItem($item)
    {
        //$itemPrecio = \App\ADMITEMPRECIOELE::where('item','=',$item)->get();
        
        $itemSerie = \App\ADMITEMSERIEELE::where('ITEM','=',$item)
        ->where('VENDIDO','=','N')
        ->select('SERIE')
        ->get();

        $datosMotor = \App\ADMDATOSMOTORELE::where('ITEM','=',$item)
        ->where('NOMBRE','MODELO')
        ->whereNull('ESTADO')
        ->select('CHASIS')
        ->get();

        return response()->json(['series'=>$itemSerie,'motor'=>$datosMotor]);
    }

    public function GetItemTodos()
    {
        //$itemPrecio = \App\ADMITEMPRECIOELE::where('item','=',$item)->get();
        
        $itemSerie = \App\ADMITEMSERIEELE::where('VENDIDO','=','N')
        ->select('SERIE','ITEM')
        ->get();

        $datosMotor = \App\ADMDATOSMOTORELE::where('NOMBRE','MODELO')
        ->whereNull('ESTADO')
        ->select('CHASIS','ITEM')
        ->get();

        return response()->json(['series'=>$itemSerie,'motor'=>$datosMotor]);
    }

    public function GetItemLiquidacion($item)
    {
        $itemLiq = \App\ADMITEMLIQELE::where('ESTADO','=','A')
        ->where('CANTIDAD','>',0)
        ->where('ITEM','=',$item)
        ->get();

        return response()->json($itemLiq);
    }

    public function GetItemLiquidacionTodos()
    {
        $itemLiq = \App\ADMITEMLIQELE::where('ESTADO','=','A')
        ->where('CANTIDAD','>',0)        
        ->get();

        return response()->json($itemLiq);
    }

}
