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

   public function GetParametrosLite()
   {
        $paramBO = \App\ADMPARAMETROBO::first();
        $paramV = \App\ADMPARAMETROV::first();


        return response()->json(['NOMBRECIA'=>$paramV->NOMBRECIA,
                                    'IVA'=>$paramV->NOMBRECIA,
                                    'BODEGAPOS'=>$paramV->BODEGAPOS,
                                    'FACLIN'=>$paramV->FACLIN,
                                    'pagina_web'=>$paramBO->pagina_web,
                                    'EPRECIO'=>$paramV->EPRECIO,
                                    'ENOTAVENTA'=>$paramV->EPRECIO
                                ]);
   }
}
