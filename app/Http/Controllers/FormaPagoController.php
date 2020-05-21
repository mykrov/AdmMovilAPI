<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormaPagoController extends Controller
{
    public  function GetFormaPago()
    {
        $formas = \App\ADMFORMAPAGO::get();
        return response()->json($formas);
    }
}
