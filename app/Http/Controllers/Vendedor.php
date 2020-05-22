<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ADMVENDEDOR;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Validator;


class Vendedor extends Controller
{
    public $successStatus = 200;

    
    public function login(Request $request){
        
        $credenciales = $request->validate([
            'codigo' => 'required|string',
            'password' => 'required|string'
        ]);
        
        $vendedorData = \App\ADMVENDEDOR::where('CODIGO',request('codigo'))
        ->where('ESTADO','A')
        ->first();

        if ($vendedorData &&  \Hash::check(request('password'), trim($vendedorData->HASH))){
           
            Auth::loginUsingId( $vendedorData->CODIGO, TRUE);
            $user = Auth::user();
            $token = $vendedorData->createToken('authToken')->accessToken;
            return response(['vendedor'=>$vendedorData,'token'=>$token]);
        }
        else{
            return response()->json(['message'=>'ErrorLogin']); 
        }
    }

    public function listado(){

        $vendedores = \App\ADMVENDEDOR::all();
        return response()->json($vendedores);
    }

    public function SetearPasswordHash(){

        $venderores = \App\ADMVENDEDOR::all();
        $contador = 0;

        foreach ($venderores as $ven) {
            if (trim($ven['HASH']) == ''){
                
                $claveWeb = $ven['CEDULA'];

                if(trim($claveWeb != '' )){
                    $hashed = Hash::make(trim($claveWeb));
                    $affected = DB::table('ADMVENDEDOR')
                    ->where('CODIGO',$ven['CODIGO'])
                    ->update(['HASH' => $hashed]);
                    $contador++;

                }
                
            }
        } 
        
        return response()->json($contador);
    }
}
