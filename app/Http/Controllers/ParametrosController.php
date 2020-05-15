<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ParametrosController extends Controller
{
   public function GetParametros(){
       $paramBO = \App\ADMPARAMETROBO::get();
       $paramV = \App\ADMPARAMETROV::get();
       $paramC = \App\ADMPARAMETROC::get();

       return response()->json(['parametroBO'=>$paramBO,'parametroV'=>$paramV,'parametroC'=>$paramC]);
   }
}
