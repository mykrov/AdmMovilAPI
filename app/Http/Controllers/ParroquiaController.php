<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ParroquiaController extends Controller
{
    public function GetParroquias(){
        $parroquias = \App\ADMPARROQUIA::where('estado','=','A')->get();
        return response()->json($parroquias);
    }
}
