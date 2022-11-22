<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemXClienteController extends Controller
{
    // Retorna las entidades del modelo consultado de acuerdo al criterio de busqueda."
    public function GetItemXCliente(){
        $items = \App\ADMITEMXCLIENTE::get();
        return response()->json($items);
    }
}
