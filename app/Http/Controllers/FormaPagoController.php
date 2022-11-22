<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormaPagoController extends Controller
{
    // Retorna las entidades del modelo consultado de acuerdo al criterio de busqueda."
    public  function GetFormaPago()
    {
        $formas = \App\ADMFORMAPAGO::get();
        return response()->json($formas);
    }
}
