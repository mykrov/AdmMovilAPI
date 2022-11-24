<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ADMVENDEDOR;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Log;
use Validator;


class Vendedor extends Controller
{
    public $successStatus = 200;

    // proceso de login en la aplicación
    public function login(Request $request){

        Log::info('Login de:');
        Log::info($request);
        
        // valida los campos en el request
        $credenciales = $request->validate([
            'codigo' => 'required|string',
            'password' => 'required|string'
        ]);

        //Logueo para Operadores longitud de 3 letras
        if(strlen(request('codigo')) == 3 ){
            
            // consulta de existencia del operador.
            $operador = \App\ADMOPERADOR::where('codigo',request('codigo'))
            ->where('estado','A')
            ->first();
            
            $userInput = str_split($request['password']);
            $UpDown = []; 
            $sig = 1;
            // decodificacián de la clave en base al estilo de BIROBID SA
            foreach ($userInput as $key => $value) {
                if($sig == 1){
                    array_push($UpDown, chr(ord($value) - 1)); 
                    $sig = 0; 
                }else{
                    array_push($UpDown, chr(ord($value) + 1)); 
                    $sig = 1;
                }
            }
                      
            // Evalua la clave con el usuario
            if ($operador && implode($UpDown) == trim($operador->clave)){
                
                // consulta el vendedor relacionado al Operador
                $vendedorRel = \App\ADMVENDEDOR::where('operadormovil',$operador->codigo)
                ->first();

                // autentifica el usuario generando el token
                try {
                    Auth::loginUsingId($operador->codigo, TRUE);
                    $user = Auth::user();
                    $token = $operador->createToken('authToken')->accessToken;
                    return response()->json(['operador'=>$operador,'token'=>$token,'ID'=>$user,'vendedor'=>$vendedorRel]);
                } catch (\Throwable $th) {
                    return response()->json(['message'=>$th->getMessage()]);
                }
            }
            else{
                return response()->json(['message'=>'ErrorLogin']);
            }    

        }else{
                 
            // el login es con una usuario Vendedor
            // se consulta la existencia del vendedor
            $vendedor = \App\ADMVENDEDOR::where('CODIGO',request('codigo'))
            ->where('ESTADO','A')
            ->first();
            
            // evalua el vendedor y la clave
            if ($vendedor && trim($request['password']) == trim($vendedor->CLAVEWEB)){
                // autoriza y crea el token
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

    }

    // Obtiene las instancias del modelo seleccionado
    public function listado(){
        $vendedores = \App\ADMVENDEDOR::all();
        return response()->json($vendedores);
    }

    // función para actualizar el campo HASH de los vendedores
    // con un hash de su clave web siempre y cuando esté vacio(por primera vez)
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
