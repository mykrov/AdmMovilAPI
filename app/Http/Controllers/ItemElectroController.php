<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemElectroController extends Controller
{
    // Retorna las entidades del modelo consultado de acuerdo al criterio de busqueda."
    public function GetItemsElectro($bodega)
    {
        $items = DB::table('ADMITEM')
        ->select(DB::raw('RTRIM(ADMITEM.ITEM) as ITEM,
        RTRIM(ADMITEM.NOMBRE) as NOMBRE,
        ADMITEMPRECIOELE.PRECIO,ADMITEMPRECIOELE.PRECIOTAR,ADMITEMPRECIOELE.PRECIOCRE,
        ADMITEMPRECIOELE.TIEMPO,
        ADMITEMPRECIOELE.TIEMPOINI,
        ADMITEMPRECIOELE.CUOTASGRATIS,
        ADMITEMPRECIOELE.ENTRADA,       
        ADMITEMPRECIOELE.INTERES,
        ADMITEMPRECIOELE.FRECUENCIA,
        ADMITEMPRECIOELE.COSTOR,
        ADMITEMPRECIOELE.INTERESF,
        ADMITEM.IVA,
        ADMITEMBOD.STOCK,
        RTRIM(ADMITEM.IMAGENADICIONAL) as IMAGENADICIONAL,
        ADMITEMPRECIOELE.TIENEREGALO,
        ADMITEMPRECIOELE.PRECIOMINIMO,
        ADMITEMBOD.BODEGA'))
        ->join('ADMITEMPRECIOELE','ADMITEMPRECIOELE.item','ADMITEM.ITEM')
        ->join('ADMITEMBOD','ADMITEMBOD.ITEM','ADMITEM.ITEM')
        ->where('ADMITEMBOD.BODEGA','=',$bodega)
        ->where('ADMITEMBOD.STOCK','>',0)
        ->get();

        return response()->json($items);
    }
    
    // Retorna las entidades del modelo consultado de acuerdo al criterio de busqueda."
    public function GetItemsElectroNombre($bodega,$nombre)
    {
        $items = DB::table('ADMITEM')
        ->select(DB::raw('RTRIM(ADMITEM.ITEM) as ITEM,
        RTRIM(ADMITEM.NOMBRE) as NOMBRE,
        ADMITEMPRECIOELE.PRECIO,ADMITEMPRECIOELE.PRECIOTAR,ADMITEMPRECIOELE.PRECIOCRE,
        ADMITEMPRECIOELE.TIEMPO,
        ADMITEMPRECIOELE.TIEMPOINI,
        ADMITEMPRECIOELE.CUOTASGRATIS,
        ADMITEMPRECIOELE.ENTRADA,       
        ADMITEMPRECIOELE.INTERES,
        ADMITEMPRECIOELE.FRECUENCIA,
        ADMITEMPRECIOELE.COSTOR,
        ADMITEMPRECIOELE.INTERESF,
        ADMITEM.IVA,
        ADMITEMBOD.STOCK,
        RTRIM(ADMITEM.IMAGENADICIONAL) as IMAGENADICIONAL,
        ADMITEMPRECIOELE.TIENEREGALO,
        ADMITEMPRECIOELE.PRECIOMINIMO,
        ADMITEMBOD.BODEGA'))
        ->leftjoin('ADMITEMPRECIOELE','ADMITEMPRECIOELE.item','ADMITEM.ITEM')
        ->join('ADMITEMBOD','ADMITEMBOD.ITEM','ADMITEM.ITEM')
        ->where('ADMITEMBOD.BODEGA','=',$bodega)
        ->where('ADMITEMBOD.STOCK','>',0)
        ->where('ADMITEM.NOMBRE','like','%'.$nombre.'%')
        ->get();

        return response()->json($items);
    }

    // Retorna las entidades del modelo consultado de acuerdo al criterio de busqueda."
    public function GetItemsElectroCod($bodega,$cod)
    {
        $items = DB::table('ADMITEM')
        ->select(DB::raw('RTRIM(ADMITEM.ITEM) as ITEM,
        RTRIM(ADMITEM.NOMBRE) as NOMBRE,
        ADMITEMPRECIOELE.PRECIO,ADMITEMPRECIOELE.PRECIOTAR,ADMITEMPRECIOELE.PRECIOCRE,
        ADMITEMPRECIOELE.TIEMPO,
        ADMITEMPRECIOELE.TIEMPOINI,
        ADMITEMPRECIOELE.CUOTASGRATIS,
        ADMITEMPRECIOELE.ENTRADA,       
        ADMITEMPRECIOELE.INTERES,
        ADMITEMPRECIOELE.FRECUENCIA,
        ADMITEMPRECIOELE.COSTOR,
        ADMITEMPRECIOELE.INTERESF,   
        ADMITEM.IVA,
        ADMITEMBOD.STOCK,
        RTRIM(ADMITEM.IMAGENADICIONAL) as IMAGENADICIONAL,
        ADMITEMPRECIOELE.TIENEREGALO,
        ADMITEMPRECIOELE.PRECIOMINIMO,
        ADMITEMBOD.BODEGA'))
        ->leftjoin('ADMITEMPRECIOELE','ADMITEMPRECIOELE.item','ADMITEM.ITEM')
        ->join('ADMITEMBOD','ADMITEMBOD.ITEM','ADMITEM.ITEM')
        ->where('ADMITEMBOD.BODEGA','=',$bodega)
        ->where('ADMITEMBOD.STOCK','>',0)
        ->where('ADMITEM.ITEM','like','%'.$cod.'%')
        ->get();

        return response()->json($items);
    }
}
