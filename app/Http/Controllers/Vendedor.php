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
        
        $vendedor = \App\ADMVENDEDOR::where('CODIGO',request('codigo'))
        ->where('ESTADO','A')
        ->first();
        
        //return response()->json($vendedorData);
        //if ($vendedor &&  \Hash::check(request('password'), trim($vendedor->HASH))){
        if ($vendedor &&  trim($request['password']) == trim($vendedor->CLAVEWEB)){
            try {
                Auth::loginUsingId($vendedor->CODIGO, TRUE);
                $user = Auth::user();
                $token = $vendedor->createToken('authToken')->accessToken;
                return response()->json(['vendedor'=>$vendedor,'token'=>$token,'ID'=>$user]);
            } catch (\Throwable $th) {
                return response()->json(['message'=>$th->getMessage()]);
            }
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
                
                $claveWeb = $ven['CLAVEWEB'];

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
