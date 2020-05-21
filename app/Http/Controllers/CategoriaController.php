<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function GetCategorias(){
        $cate = \App\ADMCATEGORIA::where('ESTADO','=','A')->get();
        return response()->json($cate);
    }
}
