<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Cliente extends Controller
{
    public function listado(){

        $clientes = \App\Cliente::all();
        return response()->json($clientes);

    }

    public function byID($id){

        $cliente = \App\Cliente::where('codigo',$id)->get();
        return response()->json($cliente);

    }

    public function ClienteXVendedor($vendedor){
        
        $clientes = \App\Cliente::where('VENDEDOR','=',$vendedor)->get();
        return response()->json($clientes);
    }

    public function CreateClient(Request $request){

        $datos = $request->nuevoCliente;

        $check = \App\Cliente::where('RUC','=',$datos->RUC)->count();

        if ($check < 1) {

            $cliBase = \App\ADMPARAMETROC::get();

            $standar = \App\Cliente::where('CODIGO','=',$cliBase->CLIENTEMODELOCARRO)->get();
    
            $ClientNew  = new \App\Cliente;
    
            //Proceso de Llenado de Datos
            //$ClientNew->CODIGO = $standar->CODIGO;
            $ClientNew->RAZONSOCIAL = $standar->RAZONSOCIAL;
            $ClientNew->NEGOCIO = $standar->NEGOCIO;
            $ClientNew->REPRESENTA = $standar->REPRESENTA;
            $ClientNew->RUC = $standar->RUC;
            $ClientNew->DIRECCION = $standar->DIRECCION;
            $ClientNew->TELEFONOS = $standar->TELEFONOS;
            $ClientNew->FAX = $standar->FAX;
            $ClientNew->EMAIL = $standar->EMAIL;
            $ClientNew->TIPO = $standar->TIPO;
            $ClientNew->CATEGORIA = $standar->CATEGORIA;
            $ClientNew->PROVINCIA = $standar->PROVINCIA;
            $ClientNew->CANTON = $standar->CANTON;
            $ClientNew->PARROQUIA = $standar->PARROQUIA;
            $ClientNew->SECTOR = $standar->SECTOR;
            $ClientNew->RUTA = $standar->RUTA;
            $ClientNew->CTACLIENTE = $standar->CTACLIENTE;
            $ClientNew->CUPO = $standar->CUPO;
            $ClientNew->GRUPO = $standar->GRUPO;
            $ClientNew->ORDEN = $standar->ORDEN;
            $ClientNew->CODFRE = $standar->CODFRE;
            $ClientNew->CREDITO = $standar->CREDITO;
            $ClientNew->DIA = $standar->DIA;
            $ClientNew->FECDESDE = $standar->FECDESDE;
            $ClientNew->FECHAING = $standar->FECHAING;
            $ClientNew->FECULTCOM = $standar->FECULTCOM;
            $ClientNew->FECELIMINA = $standar->FECELIMINA;
            $ClientNew->ESTADO = $standar->ESTADO;
            $ClientNew->TDCREDITO = $standar->TDCREDITO;
            $ClientNew->DIASCREDIT = $standar->DIASCREDIT;
            $ClientNew->VENDEDOR = $standar->VENDEDOR;
            $ClientNew->FORMAPAG = $standar->FORMAPAG;
            $ClientNew->CREDITOA = $standar->CREDITOA;
            $ClientNew->IVA = $standar->IVA;
            $ClientNew->BACKORDER = $standar->BACKORDER;
            $ClientNew->RETENPED = $standar->RETENPED;
            $ClientNew->CONFINAL = $standar->CONFINAL;
            $ClientNew->CLASE = $standar->CLASE;
            $ClientNew->OBSERVACION = $standar->OBSERVACION;
            $ClientNew->TIPONEGO = $standar->TIPONEGO;
            $ClientNew->CODTMP = $standar->CODTMP;
            $ClientNew->TIPODOC = $standar->TIPODOC;
            $ClientNew->TIPOCUENTA = $standar->TIPOCUENTA;
            $ClientNew->CLIENTEWEB = $standar->CLIENTEWEB;
            $ClientNew->CODCLIENTEDOMI = $standar->CODCLIENTEDOMI;
            $ClientNew->CLIENTEDOMI = $standar->CLIENTEDOMI;
            $ClientNew->REFERENCIA = $standar->REFERENCIA;
            $ClientNew->TIPOCONTRIBUYENTE = $standar->TIPOCONTRIBUYENTE;
            $ClientNew->RETIENEFUENTE = $standar->RETIENEFUENTE;
            $ClientNew->RETIENEIVA = $standar->RETIENEIVA;
            $ClientNew->ZONA = $standar->ZONA;
            $ClientNew->FECNAC = $standar->FECNAC;
            $ClientNew->FECMOD = $standar->FECMOD;
            $ClientNew->OPEMOD = $standar->OPEMOD;
            $ClientNew->PORDESSUGERIDO = $standar->PORDESSUGERIDO;
            $ClientNew->EMAILSECUNDARIO = $standar->EMAILSECUNDARIO;
            $ClientNew->fechaenvioweb = $standar->fechaenvioweb;
            $ClientNew->FACTURAELECTRONICA = $standar->FACTURAELECTRONICA;
            $ClientNew->CLAVEFE = $standar->CLAVEFE;
            $ClientNew->SUBIRWEB = $standar->SUBIRWEB;
            $ClientNew->TIPOPERSONA = $standar->TIPOPERSONA;
            $ClientNew->SEXO = $standar->SEXO;
            $ClientNew->ESTADOCIVIL = $standar->ESTADOCIVIL;
            $ClientNew->ORIGENINGRESO = $standar->ORIGENINGRESO;
            $ClientNew->TIPOPAGO = $standar->TIPOPAGO;
            $ClientNew->FECHADESDE = $standar->FECHADESDE;
            $ClientNew->FECHAHASTA = $standar->FECHAHASTA;
            $ClientNew->BENEFICIARIO = $standar->BENEFICIARIO;
            $ClientNew->NOCONTRATO = $standar->NOCONTRATO;
            $ClientNew->TIPOPERSONAADICIONAL = $standar->TIPOPERSONAADICIONAL;
            $ClientNew->GERENTE = $standar->GERENTE;
            $ClientNew->TELEFONO = $standar->TELEFONO;
            $ClientNew->CONTACTOPAGO = $standar->CONTACTOPAGO;
            $ClientNew->CORREO1 = $standar->CORREO1;
            $ClientNew->CORREO2 = $standar->CORREO2;
            $ClientNew->CARGO1 = $standar->CARGO1;
            $ClientNew->CARGO2 = $standar->CARGO2;
            $ClientNew->OBSERVACIONADICIONAL = $standar->OBSERVACIONADICIONAL;
            $ClientNew->PAGOCUOTAS = $standar->PAGOCUOTAS;
            $ClientNew->NUMCUOTAS = $standar->NUMCUOTAS;
            $ClientNew->CUOTA1 = $standar->CUOTA1;
            $ClientNew->CUOTA2 = $standar->CUOTA2;
            $ClientNew->CUOTA3 = $standar->CUOTA3;
            $ClientNew->CUOTA4 = $standar->CUOTA4;
            $ClientNew->CUOTA5 = $standar->CUOTA5;
            $ClientNew->EJEX = $standar->EJEX;
            $ClientNew->EJEY = $standar->EJEY;
            $ClientNew->CLIENTEMOVIL = $standar->CLIENTEMOVIL;
            $ClientNew->ESTADOPARAWEB = $standar->ESTADOPARAWEB;
            $ClientNew->CLIRELACIONADO = $standar->CLIRELACIONADO;
            $ClientNew->VENDEDORAUX = $standar->VENDEDORAUX;
            $ClientNew->CANAL = $standar->CANAL;
            $ClientNew->SUBCANAL = $standar->SUBCANAL;
            $ClientNew->grupocliente = $standar->grupocliente;
            $ClientNew->grupocredito = $standar->grupocredito;
            $ClientNew->EWEB = $standar->EWEB;
            $ClientNew->nombre = $standar->nombre;
            $ClientNew->FACTURABLE = $standar->FACTURABLE;
            $ClientNew->DEUDAS = $standar->DEUDAS;
            $ClientNew->ANTICIPO = $standar->ANTICIPO;
            $ClientNew->CIUDAD = $standar->CIUDAD;
            $ClientNew->coordenada = $standar->coordenada;
            $ClientNew->barrio = $standar->barrio;
            $ClientNew->tipodocumento = $standar->tipodocumento;
            $ClientNew->CODIGOSAP = $standar->CODIGOSAP;
            $ClientNew->CodShip = $standar->CodShip;
            $ClientNew->longuitud = $standar->longuitud;
            $ClientNew->latitud = $standar->latitud;
            $ClientNew->TipoLocalidad = $standar->TipoLocalidad;
            $ClientNew->numero = $standar->numero;
            $ClientNew->CORREOCARRO = $standar->CORREOCARRO;
            $ClientNew->CLAVECARRO = $standar->CLAVECARRO;

            return response($request);


        } else {
            return response()->json(['result'=>'IdentificacionExistente']);
        }
        
    }
}
