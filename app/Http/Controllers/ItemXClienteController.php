<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemXClienteController extends Controller
{
    public function GetItemXCliente(){
        $items = \App\ADMITEMXCLIENTE::get();
        return response()->json($items);
    }
}
