<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Item extends Controller
{
    public function ItemXBodega($bodega){
       
        $items2 = DB::select(DB::raw("exec SP_ITEMS_APPMOVIL :Param1"),[
            ':Param1' => $bodega
        ]);

        return response()->json($items2);
    }
}
