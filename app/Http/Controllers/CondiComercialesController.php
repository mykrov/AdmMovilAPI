<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CondiComercialesController extends Controller
{
    public function GetCondiComer(){
        $fecha = Carbon::now();
        $condiciones = \App\ADMCONDICOMER::get();

        return response()->json($condiciones);
    }

    public function GetCondiComerPorProducto($item){
        $fecha = Carbon::now();
        $condicionesi = \App\ADMCONDICOMER::where('ITEM','=',$item)->get();
        //->where('FECDESDE','>=','01-01-'.$fecha->Format('Y'))
        //->where('FECHASTA','>=',$fecha->Format('d-m-Y'))
        

        return response()->json($condicionesi);
    }
}
