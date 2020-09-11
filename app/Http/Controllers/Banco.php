<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Banco extends Controller
{
   public function GetBancos(){

      $bancos = \App\ADMBANCO::where('estado','=','A')->get();
      return response()->json($bancos);

   }

   public function GetBancosCia(){
      
   }
}
