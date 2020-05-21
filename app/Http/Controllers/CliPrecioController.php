<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CliPrecioController extends Controller
{
   public function GetCliPrecio()
   {
       $cprecio = \App\ADMCLIPRECIO::get();
       return response()->json($cprecio);
   }
}
