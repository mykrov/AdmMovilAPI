<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RangoPrecio extends Controller
{
    // Verifica si un item siene rango de precios y retorna el resultado
    public function RangoPrecioItem($it)
    {
        $tienerango = 'si';
        $cont = \App\ADMRANGOPRECIO::where('codigoitem','=',"$it")->count();

        if($cont == 0) {
            $tienerango = 'no';
            $item  = [];
        }else{
            $item  = \App\ADMRANGOPRECIO::where('codigoitem','=',"$it")
            ->orderBy('minimorango', 'ASC')->get();
        }  
        return response()->json(['tieneRango'=>$tienerango,'data'=>$item]);
    }

    // Verifica si un item por punto tiene rango de precios y retorna el resultado
    public function RangoPrecioItemPunto($it,$punto)
    {
        $tienerango = 'si';
        $cont = \App\ADMRANGOPRECIO::where('codigoitem','=',"$it")
        ->where('punto','=',$punto)
        ->count();

        if($cont == 0) {
            $tienerango = 'no';
            $item  = [];
        }else{
            $item  = \App\ADMRANGOPRECIO::where('codigoitem','=',"$it")
            ->where('punto','=',$punto)
            ->orderBy('minimorango', 'ASC')->get();
        }  
        return response()->json(['tieneRango'=>$tienerango,'data'=>$item]);
    }
}
