<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Item extends Controller
{
    public function ItemXBodega($bodega)
    {
       
        $items2 = DB::select(DB::raw("exec SP_ITEMS_APPMOVIL :Param1"),[
            ':Param1' => $bodega
        ]);

        return response()->json($items2);
    }

    public function ItemBodegaXCategoria($bodega,$categoria)
    {
        
        $items2 = DB::select(DB::raw("exec SP_ITEMS_APPMOVIL_CATE :Param1,:Param2"),[
            ':Param1' => $bodega,
            ':Param2' => $categoria
        ]);

        return response()->json($items2);

    }

    public function ItemXCodigo($codigo)
    {
        $item3 = \App\ADMITEM::where('ITEM',$codigo)->first();
        return response()->json($item3);
    }
}
