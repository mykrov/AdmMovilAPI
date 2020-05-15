<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CondiComercialesController extends Controller
{
    public function GetCondiComer(){
        $condiciones = \App\ADMCONDICOMER::get();

        return response()->json($condiciones);
    }
}
