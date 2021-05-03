<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemElectroController extends Controller
{
    public function GetItemsElectro($bodega)
    {
        $items = DB::table('ADMITEM')
        ->select(DB::raw('RTRIM(ADMITEM.ITEM) as ITEM,
        RTRIM(ADMITEM.NOMBRE) as NOMBRE,
        ADMITEMPRECIOELE.PRECIO,
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
    
    public function GetItemsElectroNombre($bodega,$nombre)
    {
        $items = DB::table('ADMITEM')
        ->select(DB::raw('RTRIM(ADMITEM.ITEM) as ITEM,
        RTRIM(ADMITEM.NOMBRE) as NOMBRE,
        ADMITEMPRECIOELE.PRECIO,
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

    public function GetItemsElectroCod($bodega,$cod)
    {
        $items = DB::table('ADMITEM')
        ->select(DB::raw('RTRIM(ADMITEM.ITEM) as ITEM,
        RTRIM(ADMITEM.NOMBRE) as NOMBRE,
        ADMITEMPRECIOELE.PRECIO,
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