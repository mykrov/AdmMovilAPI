<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemTopController extends Controller
{
    // Retorna las entidades del modelo consultado de acuerdo al criterio de busqueda."
    public function GetItemTop(){
        $items = \App\ADMITEMTOP::get();
        return response()->json($items);
    }
}
