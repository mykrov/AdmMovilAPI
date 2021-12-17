<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ElectroController extends Controller
{
    public function GetItem($item,$bodega)
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

    public function GetItemTodos($bod)
    {
        //$itemPrecio = \App\ADMITEMPRECIOELE::where('item','=',$item)->get();
        
        $itemSerie = \App\ADMITEMSERIEELE::where('VENDIDO','=','N')
        ->where('BODEGA',$bod)
        ->select('SERIE','ITEM')
        ->get();

        $datosMotor = \App\ADMDATOSMOTORELE::where('NOMBRE','MODELO')
        ->whereNull('ESTADO')
        ->where('BODEGAAJI',$bod)
        ->select('CHASIS','ITEM')
        ->get();

        return response()->json(['series'=>$itemSerie,'motor'=>$datosMotor]);
    }

    public function GetItemRegalosTodos($bod){

        $itemsr = DB::table('ADMITEM')
        ->join('ADMITEMBOD','ADMITEMBOD.ITEM','ADMITEM.ITEM')
        ->where('ADMITEMBOD.BODEGA',$bod)
        ->where('ADMITEM.STOCK','>',0)
        ->where('ADMITEM.REGALO','S')
        ->select([
            
            DB::raw('RTRIM(ADMITEM.ITEM) as ITEM'),
            DB::raw('RTRIM(ADMITEM.NOMBRE) as NOMBRE'),           
            'ADMITEM.STOCK',
            'ADMITEMBOD.BODEGA',
            DB::raw('ROUND(ADMITEM.PRECIO1,2) as PRECIO')
            
        ])
        ->get();

        return response()->json($itemsr);

    }

    public function GetItemLiquidacion($item,$bodega)
    {
        $itemLiq = \App\ADMITEMLIQELE::where('ESTADO','=','A')
        ->where('CANTIDAD','>',0)
        ->where('ITEM','=',$item)
        ->get();

        return response()->json($itemLiq);
    }

    public function GetItemLiquidacionTodos($bodega)
    {
        $itemLiq = \App\ADMITEMLIQELE::where('ESTADO','=','A')
        ->where('CANTIDAD','>',0)        
        ->get();

        return response()->json($itemLiq);
    }

}
