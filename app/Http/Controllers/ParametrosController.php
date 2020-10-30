<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ParametrosController extends Controller
{
   public function GetParametros(){
       $paramBO = \App\ADMPARAMETROBO::get();
       //$paramV = \App\ADMPARAMETROV::get();
       //$paramC = \App\ADMPARAMETROC::get();

       return response()->json(['parametroBO'=>$paramBO]);
   }

   public function GetParametrosLite()
   {
        $paramBO = \App\ADMPARAMETROBO::first();
        $paramV = \App\ADMPARAMETROV::first();


        return response()->json(['NOMBRECIA'=>$paramV->NOMBRECIA,
                                    'IVA'=>$paramV->IVA,
                                    'BODEGAPOS'=>$paramV->BODEGAPOS,
                                    'FACLIN'=>$paramV->FACLIN,
                                    'pagina_web'=>$paramBO->pagina_web,
                                    'EPRECIO'=>$paramV->EPRECIO,
                                    'ENOTAVENTA'=>$paramV->EPRECIO,
                                    'LISTADEPRECIO'=>$paramV->LISTADEPRECIO,
                                    'LEYENDAPROFORMA'=>$paramV->LEYENDAPROFORMA,
                                    'usardecimales'=>$paramV->usardecimales,
                                    'ESQUEMAELE'=>$paramV->ESQUEMAELE,
                                    'FACLIN'=>$paramV->FACLIN,
                                    'NVTLIN'=>$paramV->NVTLIN,
                                    'POSFACLIN' => $paramV->POSFACLIN
                                ]);
   }
}
