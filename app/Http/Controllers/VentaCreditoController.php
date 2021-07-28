<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use \App\ADMBODEGA;
use \App\ADMPARAMETROV;
use \App\ADMPARAMETROBO;
use \App\Cliente;
use \App\ADMDETEGRBOD;
use \App\ADMCABEGRBOD;
use \App\ADMDEUDA;
use \App\ADMCREDITO;
use \App\Mail\FacturaInvoice;
use \App\ADMDETEGRESO;


class VentaCreditoController extends Controller
{
    
    public function PostVentaCre(Request $r)
    {
        $cabecera = $r->cabecera[0];
        $detalles = $r->detalles;
        Log::info(['cab'=>$cabecera,'detalle'=>$detalles]);
        //return response()->json(['CAB'=>$r]);

        $detallesContador = COUNT($detalles);
        
        if($detallesContador == 0){
            return response()->json(['error'=>'Cabecera Sin Detalles']);
        }

        $operador1 = '';

        
        //Datos del Operador segun vendedor
        $vendedorData = \App\ADMVENDEDOR::where('CODIGO','=',$cabecera['usuario'])->first();
        $operador1 = '';
        if($vendedorData == null || $vendedorData->operadormovil == null){
            $operador1 = 'ADM';
        }
     

       
        $parametrov = ADMPARAMETROV::first();
        $secuencialNew = $parametrov->SECUENCIAL;
        $parametrov->SECUENCIAL = $parametrov->SECUENCIAL + 1;
        $parametrov->save();


        DB::beginTransaction();

        //En caso de Observacion.
        $observacion = "Gracias por su Compra.";
        if(trim($cabecera['observacion']) != ""){
            $observacion = $cabecera['observacion'];
        }

        $campo_adi = "Gracias por su Compra.";
        if(trim($cabecera['datos_adi']) != ""){
            $campo_adi = $cabecera['datos_adi'];
        }

        try {
        
            $bodega = ADMBODEGA::where('CODIGO','=',$cabecera['bodega'])->first();
            $cliente = Cliente::where('CODIGO','=',$cabecera['cliente'])->first();
            $parametrobo = ADMPARAMETROBO::first();

            
            $grabaIva = "N";
            if(floatval($cabecera['iva']) > 0){
                $grabaIva = "S";
            }
            
            $date = Carbon::now()->subHours(5);
            
            $cab = new \App\ADMCABEGRESO();
            
            $cab->TIPO = $cabecera['tipo']; 
            $cab->BODEGA = intval($cabecera['bodega']); 
             
            if($cabecera['tipo'] == 'NVT'){
                $cab->NUMERO = $bodega->NONOTA + 1;
            }else{
                $cab->NUMERO = $bodega->NOFACTURA + 1;
            }

            $cab->SERIE = trim($bodega->SERIE); 
            $cab->SECUENCIAL = $secuencialNew + 1; 
            $cab->NUMPROCESO = null; 
            $cab->NUMPEDIDO = 0; 
            $cab->NUMGUIA = null; 
            $cab->CAMION = null; 
            $cab->CHOFER = null; 
            $cab->DOCREL = null; 
            $cab->NUMEROREL = null; 
            $cab->FECHA = $date->Format('Y-d-m'); 
            $cab->FECHAVEN = $date->addDays(intval($cliente->DIASCREDIT))->format('Y-d-m'); 
            $cab->FECHADES = $cabecera['fecha_ingreso']; 
            $cab->OPERADOR = $operador1; 
            $cab->CLIENTE = $cabecera['cliente']; 
            $cab->VENDEDOR = $cabecera['usuario']; 
            $cab->PROVEEDOR = null; 

            $cab->SUBTOTAL = round($cabecera['subtotal'],2); 
            $cab->DESCUENTO = round($cabecera['descuento'],2); 
            $cab->IVA = round($cabecera['iva'],2); 
            $cab->NETO = round($cabecera['neto'],2); 

            $cab->TRANSPORTE = 0; 
            $cab->RECARGO = 0; 
            $cab->BODEGADES = 0; 
            $cab->PESO = 0; 
            $cab->VOLUMEN = 0; 
            $cab->MOTIVO = null; 
            $cab->ESTADO = "P"; 
            $cab->ESTADODOC = null; 
            $cab->TIPOVTA = "BIE"; 
            $cab->INTECXC = null; 
            $cab->OBSERVA =  $observacion; 
            $cab->COMENTA = $campo_adi;
            $cab->INTEGRADO = null; 
            $cab->SECCON = 0; //Pendiente, se Actualiza al guardar todo.
            $cab->NUMSERIE = null; 
            $cab->NOCARGA = null; 
            $cab->APLSRI = null; 
            $cab->NUMFISICO = null; 
            $cab->HORA = $cabecera['hora_ingreso']; 
            $cab->NOMBREPC = "Servidor - Laravel 2021"; 
            $cab->claseAjuEgreso = null; 
            $cab->SUBTOTAL0 = 0; 
            $cab->NUMGUIATRANS = null; 
            $cab->GRAVAIVA = $grabaIva; 
            $cab->CREDITO = $cabecera["credito"]; 
            $cab->ESTADODESPACHO = "N"; 
            $cab->SECAUTOVENTA = null; 
            $cab->NUMCUOTAS = $cabecera["numCuotas"]; 
            $cab->NUMGUIAREMISION = $bodega->NUMGUIAREMISION + 1; 
            $cab->SBTBIENES = round($cabecera['subtotal'],2); 
            $cab->SBTSERVICIOS = 0; 
            $cab->TIPOCLIENTE = trim($cliente->TIPO); 
            $cab->SUCURSAL = ""; 
            $cab->ACT_SCT = "N"; 
            $cab->NUMPRODUCCION = null; 
            $cab->porentregar = "N"; 
            $cab->ENTREGADA = "N"; 
            $cab->REFERENCIA = null; 
            $cab->FECHA_EMBARQUE = null; 
            $cab->BUQUE = null; 
            $cab->NAVIERA = null; 
            $cab->ALMACENARA = null; 
            $cab->PRODUCTO =null; 
            $cab->CODIGORETAILPRO = null; 
            $cab->GRMFACELEC = "N"; 
            $cab->NOAUTOGRM = null; 
            $cab->mescredito = 0; 
            $cab->tipopago = $cabecera["tipoPago"]; 
            $cab->numeropagos = $cabecera["numeroPagos"]; 
            $cab->mesescredito = $cabecera["mesesCredito"];
            $cab->entrada = $cabecera["entrada"]; 
            $cab->valorfinanciado = $cabecera["valorFinanciado"]; 
            $cab->porinteres = $cabecera["porInteres"]; 
            $cab->montointeres = $cabecera["montoInteres"]; 
            $cab->totaldeuda = $cabecera["totalDeuda"]; 
            $cab->xsubtotal = $cabecera["xSubtotal"]; 
            $cab->xsubtotal0 = $cabecera["xSubtotal0"]; 
            $cab->fechainipago = $cabecera["fechaIniPago"];
            $cab->xdescuento = 0; 
            $cab->xdescuento0 = 0; 
            $cab->xiva = 0; 
            $cab->numerosolicitud = 0;              
            $cab->ENVIADONESTLE = "N"; 
            $cab->tipotienda = null; 
            $cab->CodShip = null; 
            $cab->NUMPESAJE = 0; 
            $cab->ESFOMENTO = "N";            

            $lengh_str = 9;
            $secuencial_str = strval($cab->NUMERO);
            $seC_cero = substr("000000000{$secuencial_str}",-$lengh_str);
            
            $claveAcc = $this->GenerarClave($seC_cero,$cab->SERIE,$parametrobo->ruc,'01',1,2,$cab->FECHA);
            $cab->NUMAUTO = $claveAcc;

            $cab->save();

            //Generar Cabecera de Egreso.
            $cabEgr = new ADMCABEGRBOD();

            $cabEgr->SECUENCIAL = $cab->SECUENCIAL;
            $cabEgr->BODEGA = $cab->BODEGA;
            $cabEgr->TIPO = $cab->TIPO;
            $cabEgr->NUMERO = $cab->NUMERO;
            $cabEgr->NUMEGRESO = $bodega->NOEGR + 1;
            $cabEgr->FECHA = $date->Format('Y-d-m');
            $cabEgr->ESTADO = "P";

            $cabEgr->save();
            
            if($cabecera['tipo'] == 'NVT'){
                $bodega->NONOTA = $bodega->NONOTA + 1;
            }else{
                $bodega->NOFACTURA = $bodega->NOFACTURA + 1;
            }
            
            $bodega->NUMGUIAREMISION = $cab->NUMGUIAREMISION;
            $bodega->NOEGR = $bodega->NOEGR + 1;
            
            $lineaDet = 1;

            //variables para comprobante contable
            $costo0_det = 0;
            $costo_det = 0;
            $subtotal0_det = 0;
            $subtotal_det = 0;
            $desc0_det = 0;
            $desc_det = 0;
            
            //Procesado de los Detalles.
            foreach ($detalles as $det) {
                
                $d = new \App\ADMDETEGRESO;
                
                $grabaIvadet = "N";
                if(floatval($det['iva']) > 0){
                    $grabaIvadet = "S";


                }

                $itemData = \App\ADMITEM::where('ITEM','=',trim($det['item']))->first();
                $itemElectData = \App\ADMITEMPRECIOELE::where('ITEM','=',trim($det['item']))->first();

                $d->SECUENCIAL = intval($cab->SECUENCIAL);
                $d->LINEA = intval($det['linea']);
                $d->ITEM = $det['item'];
                $d->TIPOITEM = $det['tipo_item'];
                $d->PRECIO = floatval($det['precio']);
                $d->COSTOP = $itemElectData->costo;
                $d->COSTOU = $itemElectData->costo;
                $d->CANTIU = intval($det['total_unidades']) % $itemData->FACTOR;
                $d->CANTIC = intval($det['total_unidades']  / $itemData->FACTOR);
                $d->CANTFUN = intval($det['total_unidades']);
                $d->CANTDEV = null;
                $d->SUBTOTAL = floatval($det['subtotal']);
                $d->DESCUENTO = floatval($det['descuento']);
                $d->IVA = floatval($det['iva']);
                $d->NETO = floatval($det['neto']);
                $d->PORDES = floatval($det['pordes']);
                $d->LINEAREL = null;
                $d->TIPOVTA = "V";
                $d->FORMAVTA = $det['forma_venta'];
                $d->GRAVAIVA = $grabaIvadet;
                $d->PORDES2 = 0;
                $d->CANTENTREGADA = 0;
                $d->CANTPORENTREGAR = null;
                $d->DETALLE = $campo_adi;
                $d->FECHAELA = null;
                $d->FECHAVEN = null;
                $d->LOTE = null;
                $d->preciox = 0;

                if($det['serialItem'] == null and $det['forma_venta'] == 'R' ){
                    $d->serialitem = 'XXXXXX';
                }elseif($det['serialItem'] == null and $det['forma_venta'] == 'V' ){
                    $d->serialitem = '';
                }else{
                    $d->serialitem = $det['serialItem'];
                }

                $d->save();
               
                //Bajar Stock del item.
                if($det['item'] != 'INTFIN'){
                    $itemData->STOCK = $itemData->STOCK - $d->CANTFUN;
                    $itemData->save();

                    //Bajar Stock de la Bodega del ITEM utilizando otro metodo ya que no posee PK.
                    $itemBog = \App\ADMITEMBOD::where('ITEM',trim($d->ITEM))
                    ->where('BODEGA',$cab->BODEGA)
                    ->first();

                    $stockBod = $itemBog->STOCK - $d->CANTFUN;

                    $result = DB::table('ADMITEMBOD')
                                ->where('ITEM', trim($d->ITEM))
                                ->where('BODEGA',$cab->BODEGA)
                                ->update([
                                    'STOCK' =>$stockBod,
                                    'ULTFECEGR' => Carbon::now()->subHours(5)->Format('Y-d-m')
                                ]);

                    Log::info("modifica stock");

                }              
                
                //Generar el Detalle de Egreso.

                $serial = $d->serialitem;
                $detEgr2 = new \App\ADMDETEGRBOD();
                $detEgr2->SECUENCIAL = $cab->SECUENCIAL;
                $detEgr2->ITEM = $d->ITEM;
                $detEgr2->CANTIU = $d->CANTFUN;
                $detEgr2->CANTIC = $d->CANTIC; 
                $detEgr2->COSTOP = $d->COSTOP;
                $detEgr2->COSTOU = $d->COSTOU;
                $detEgr2->CANTFUN = $d->CANTFUN;
                $detEgr2->LINEA = $lineaDet;
                //Log::info("Detalle EgresoBod ",['objeto'=>$detEgr2]);
                
                $detEgr2->save();

                $derEgr3 = ADMDETEGRBOD::where("SECUENCIAL","$cab->SECUENCIAL")
                ->where("ITEM",$d->ITEM)
                ->first();
                $derEgr3->SERIALITEM =$serial;
                $derEgr3->save();

                $lineaDet++;

                //Log::info("Guarda det egreso");

                $tipoItem = $this->tipoItemElec($detEgr2->ITEM);
                //Log::info($tipoItem);

                if($tipoItem != 'BASICO')
                {
                    //Log::info('Tipo de item',['Resultado:'=>$tipoItem]);
                    if ($tipoItem == 'ELECTRODOMESTICO') {
                        
                        $itemElectro = DB::table('ADMITEMSERIEELE')
                        ->where('ITEM', trim($d->ITEM))
                        ->where('SERIE', $d->serialitem)
                        ->where('VENDIDO','N')
                        ->update([
                            'SECUENCIALFAC' => $cab->SECUENCIAL,
                            'SERIEFAC' => $cab->SERIE,
                            'NUMEROFAC' => $cab->NUMERO,
                            'TIPOFAC' => $cab->TIPO,
                            'FECHAFAC' => Carbon::now()->subHours(5)->Format('Y-d-m'),
                            'VENDIDO' => 'S'
                        ]);

                    } elseif($tipoItem == 'MOTO') {
                        
                        $itemMoto = DB::table('ADMDATOSMOTORELE')
                        ->where('ITEM', trim($d->ITEM))
                        ->where('CHASIS', $d->serialitem)
                        ->whereNull('ESTADO')
                        ->whereNull('NUMEROFAC')
                        ->update([
                            'SECUENCIALFAC' => $cab->SECUENCIAL,
                            'SERIEFAC' => $cab->SERIE,
                            'NUMEROFAC' => $cab->NUMERO,
                            'TIPOFAC' => $cab->TIPO,
                            'BODEGAFAC' => $cab->BODEGA,
                            'ESTADO' => 'V'
                        ]);
                    }
                }

            }

            $bodega->save();
            
            //Generar la Deuda.
            $deuda = new ADMDEUDA();
            $deuda->SECUENCIAL = $cab->SECUENCIAL + 1;
            $deuda->BODEGA = $cab->BODEGA;
            $deuda->CLIENTE = $cab->CLIENTE;
            $deuda->TIPO = $cab->TIPO;
            $deuda->NUMERO = $cab->NUMERO;
            $deuda->SERIE = $cab->SERIE;
            $deuda->SECINV = $cab->SECUENCIAL;
            $deuda->IVA = round($cab->IVA,2);
            $deuda->MONTO = round($cab->NETO,2);
            $deuda->CREDITO = 0;
            $deuda->SALDO = round($cab->NETO,2);
            $deuda->FECHAEMI = $cab->FECHA;
            $deuda->FECHAVEN = $cab->FECHAVEN;
            $deuda->FECHADES = $cab->FECHA;
            $deuda->OPERADOR = $operador1;
            $deuda->VENDEDOR = $cab->VENDEDOR;
            $deuda->OBSERVACION = $observacion;
            $deuda->NUMAUTO = "";
            $deuda->BODEGAFAC = 0;
            $deuda->SERIEFAC = "";
            $deuda->NUMEROFAC = 0;
            $deuda->ACT_SCT = "N";
            $deuda->montodocumento = 0;
            $deuda->tipoventa = $cabecera['tipoVenta']; // 'CRE'
            $deuda->mesescredito = $cabecera['mesesCredito'];
            $deuda->tipopago = "";
            $deuda->numeropagos = $cabecera['numeroPagos'];
            $deuda->entrada = $cabecera['entrada'];
            $deuda->valorfinanciado = $cabecera['valorFinanciado'];
            $deuda->porinteres = $cabecera['porInteres'];
            $deuda->montointeres = $cabecera['montoInteres'];
            $deuda->totaldeuda = $cabecera['totalDeuda'];
            $deuda->seccreditogen = 0;
            $deuda->secdeudagen = 0;
            $deuda->numcuotagen = 0;
            $deuda->porinteresmora = 0;
            $deuda->basecalculo = 0;
            $deuda->diasatraso = 0;
            $deuda->usuarioeli = 0;
            $deuda->EWEB = "N";
            //$deuda->ESTADOLIQ = "N";

            //Crear Nota de Debito en caso de entrada
            if($cab->entrada > 0){
                //Log::info("Tiene Entrada");
                //Log::info(["cab fecha"=>$cab->FECHA]);
                if($this->CrearNotaDebito($cab->entrada,$deuda->SECUENCIAL + 1,$deuda->SECINV,$cab->CLIENTE,$observacion,
                $cab->VENDEDOR,$operador1,$cabEgr->BODEGA,$cab->NUMERO,$cabEgr->BODEGA,$cab->SERIE,$cab->FECHA,$deuda->mesescredito)){
                    Log::info("se generó la nota de debito por entrada.");
                    $parametrov->SECUENCIAL =  $parametrov->SECUENCIAL + 1;

                }                
            }
            
            //Generar el Credito.
            $credito = new \App\ADMCREDITO();
            $credito->SECUENCIAL = $deuda->SECUENCIAL;
            $credito->BODEGA = $deuda->BODEGA;
            $credito->CLIENTE = $deuda->CLIENTE;
            $credito->TIPO = $deuda->TIPO;
            $credito->NUMERO = $deuda->NUMERO;
            $credito->SERIE = $deuda->SERIE;
            $credito->SECINV = $deuda->SECINV;
            $credito->FECHA = $deuda->FECHA;
            $credito->MONTO = round($deuda->MONTO,2) ;
            $credito->SALDO = round($deuda->MONTO,2) ;
            $credito->OPERADOR = $operador1;
            $credito->OBSERVACION =  $observacion;
            $credito->VENDEDOR = $deuda->VENDEDOR;
            $credito->estafirmado = "N";
            $credito->ACT_SCT = "N";
            $credito->seccreditogen = 0 ;

            $parametrov = ADMPARAMETROV::first();
            $deuda->save();
            $credito->save();
            $parametrov->SECUENCIAL =  $parametrov->SECUENCIAL + 1;
            $parametrov->save();
            
            //$claveAcc = $this->GenerarClave('001391200','002152','0991503102001','01','1','2','07-02-2020');
           
            //Asiento Contable.
            $punto = substr($cab->SERIE,0,3);
            //Log::info(["Punto de la serie "=>$punto]);
            
            $secContable = $this->asientoContable(
                $cab->xsubtotal0,
                $cab->xsubtotal,
                $cab->totaldeuda,
                $cab->xiva,
                $cab->xdescuento0,
                $cab->xdescuento,
                $cab->xsubtotal0,
                $cab->xsubtotal,
                $cab->xsubtotal0,
                $cab->xsubtotal,
                trim($cliente->RAZONSOCIAL),
                $observacion,
                $operador1,
                $punto
            );
            //Log::info(["secuencial conable "=>$secContable ]);
            
            $cab->SECCON = $secContable;
            $cab->save();            

            //Crear las DeudaCuotas
            if($deuda->tipoventa == 'CRE'){
                //Log::info(["Fecha de inicio de pago "=> $cab->fechainipago]);

                if($this->CrearCuotas($cabecera['mesesCredito'],$deuda->MONTO,$deuda->numeropagos,$deuda->SECUENCIAL,$cab->fechainipago,$cab->tipopago) == false){
                    DB::rollback();
                    Log::error("Error Creando las ADMDEUDACUOTAS");
                }else{
                    Log::info("Creadas cuotas del credito");
                }
            }else{
                //Log::info("Venta de contado sin cuotas.");
            }
           
            //Guardado de todo en caso de exito en las operaciones.
            DB::commit();

            Log::info("Paso comit de la base");

            $order = $cab;
            $detalles = \App\ADMDETEGRESO::where('SECUENCIAL',$cab->SECUENCIAL)->get();
            $pdf = \PDF::loadView('pdfs/pdffactura2',['cabecera'=>$order,'cliente'=>$cliente,'parametrobo'=>$parametrobo,'detalles'=>$detalles]);

            $vendedor = \App\ADMVENDEDOR::where('CODIGO','=',$cabecera['usuario'])->first();
            $vendEmail = $vendedor->email;

            
            //Validacion de Email del Cliente.
            $clienteEmail = trim($cliente->EMAIL);
            if(trim($cabecera['email'] != null)){
                if(filter_var(trim($cabecera['email'], FILTER_VALIDATE_EMAIL))){
                    $clienteEmail = $cabecera['email'];
                }
            }
          
            //Tipo Doc para Email
            $tipoDocumento = "Factura";
            if($cabecera['tipo'] == 'NVT'){
                $tipoDocumento = "NotaPedido";
            }
            
            $nombreMail = env("MAIL_USERNAME","facturas@sistemas.com");

            if (filter_var($clienteEmail, FILTER_VALIDATE_EMAIL)) {
                //Email Valido
                try {
                    Mail::send('emails.FacPDF', ['pdf'=>$pdf,'cliente'=>trim($cliente)], function ($mail) use ($pdf,$clienteEmail,$vendEmail,$tipoDocumento,$nombreMail) {
                        $mail->from($nombreMail,$tipoDocumento.' Electronica');
                        $mail->to($clienteEmail);
                        $mail->cc($vendEmail);
                        $mail->subject($tipoDocumento.' PDF');
                        $mail->attachData($pdf->output(), $tipoDocumento.'.pdf');
                    });
                } catch (\Exception $e) {
                    return response()->json(["estado"=>"guardado", "Nfactura"=>$cab->NUMERO, "secuencial"=>$cab->SECUENCIAL,"email"=>"error_enviando"]);
                    //return response()->json(["error"=>["info Email ambos"=>$e->getMessage()]]);;
                }
                return response()->json(["estado"=>"guardado", "Nfactura"=>$cab->NUMERO, "secuencial"=>$cab->SECUENCIAL,"email"=>"enviado"]);
                //return response()->json(["estado"=>"guardado", "Nfactura"=>$cab->NUMERO, "secuencial"=>$cab->SECUENCIAL]);
               
            } else {
                //Email No valido
                try {
                    Mail::send('emails.FacPDF', ['pdf'=>$pdf,'cliente'=>trim($cliente)], function ($mail) use ($pdf,$vendEmail,$tipoDocumento,$nombreMail) {
                        $mail->from($nombreMail, $tipoDocumento.' Electronica');
                        $mail->to($vendEmail);
                        $mail->subject($tipoDocumento.' PDF');
                        $mail->attachData($pdf->output(), $tipoDocumento.'.pdf');
                    });
                } catch (\Exception $e) {
                    return response()->json(["estado"=>"guardado", "Nfactura"=>$cab->NUMERO, "secuencial"=>$cab->SECUENCIAL,"email"=>"error_enviando"]);
                    //return response()->json(["error"=>["info Email solo Vendedor:"=>$e->getMessage()]]);
                }
                return response()->json(["estado"=>"guardado", "Nfactura"=>$cab->NUMERO, "secuencial"=>$cab->SECUENCIAL,"email"=>"enviado"]);
               
            }

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return response()->json(["error"=>["info"=>$e->getMessage()]]);
        }
    }
    
    //Vista para ver la Generacion de un PDF desde el Navegador.
    public function Facturapdf(){
        $order = \App\ADMCABEGRESO::where('NUMERO',2910)
        ->whereIn('TIPO',['FAC','NVT'])
        ->orderby('SECUENCIAL','DESC')
        ->first();

        $parametrobo = ADMPARAMETROBO::first();
        $cliente = Cliente::where('CODIGO','=',$order->CLIENTE)->first();

        $detalles = \App\ADMDETEGRESO::where('SECUENCIAL',$order->SECUENCIAL)->get();
        return \PDF::loadView('pdfs.pdffactura2',['cabecera'=>$order,'cliente'=>$cliente,'parametrobo'=>$parametrobo,'detalles'=> $detalles])->stream('archivo.pdf');
    }

    //envio de email de test

    public function TestEmail(){
        try {
            Mail::send('emails.TestEmailServer',[], function ($mail) {
                $mail->from(env("MAIL_USERNAME"), 'Test de Email');
                $mail->to('salvatorex89@gmail.com');
                $mail->subject('Test envio email.');
            });            
            return "Mensaje enviado";
        } catch (\Throwable $th) {
            return $th->getMessage();
        }       
    }

    //Funcion para Clave de Acceso.
    public function SumaPorDigito($string5)
    {
        try {

            $pivote = 2;
            $longitudCadena = strlen($string5);
            $cantidadTotal = 0;
            $b = 1;

            $longi = $longitudCadena;
    
            for ($i = 0; $i < $longi; $i++) 
            {
               if($pivote == 8) {
                   $pivote = 2;
                   
                } 
               $substr = substr($string5,$i ,1);
               $temporal = (int)$substr;
               $b++;
               $temporal = (int)$temporal * (int)$pivote;
               $pivote++;
               $cantidadTotal = (int)$cantidadTotal + (int)$temporal;
            }
    
            $cantidadTotal = 11 - $cantidadTotal % 11;
            
            return $cantidadTotal;
        } catch (\Exception $e) {
            return response()->json(["error"=>["info"=>$e->getMessage()]]);
        }

    }

    //Funcion Principal Clave de Acceso.
    public function GenerarClave($secuencial6,$Serie6,$ruc,$codDoc,$tipoemision,$ambiente,$fecha_fac){
        try {
            $dat = Carbon::now()->subHours(5);
            $dateDMY = $dat->Format('d-m-Y');
            $obtender_fecha = str_replace('-','',$dateDMY);
            $codigo_Numerico = '12345678';
    
            $ret_claveAcceso = $obtender_fecha.''.$codDoc.''.$ruc.''.$ambiente.''.$Serie6.''.$secuencial6.''.$codigo_Numerico.''.$tipoemision;
    
            $cadenaInvertida = strrev($ret_claveAcceso);

            $res = $this->SumaPorDigito($cadenaInvertida);
           
            if (intval($res) == 11) {
                $ret_claveAcceso = $ret_claveAcceso."0";
            }elseif(intval($res) == 10){
                $ret_claveAcceso = $ret_claveAcceso."1";
            }else{
                $ret_claveAcceso = $ret_claveAcceso.strval($res);
            }
            return $ret_claveAcceso;
        } catch (\Excepyion $e) {
            return false;
        }

    }

    public function tipoItemElec($item)
    {
        $tipo = 'BASICO';

        $conserie = DB::table('ADMITEMSERIEELE')
        ->where('ITEM','=',$item)
        ->count();

        if ($conserie > 0) {
            $tipo = 'ELECTRODOMESTICO';
            return $tipo;
        } else {
            $moto = DB::table('ADMDATOSMOTORELE')
            ->where('ITEM','=',$item)
            ->count();

            if ($moto > 0) {
                $tipo = 'MOTO';
                return $tipo;
            } else {
                return $tipo;
            } 
        }
    }

    public function asientoContable($subTotal0,$subTotal,$neto,$iva,$desc0,$desc,$cost0,$cost,$inv0,$inv,$nombreCli,$observa,$opera,$punto)
    {
        try {
            
            $paramBO = \App\ADMPARAMETROBO::first();
            $dat = Carbon::now()->subHours(5);
            $dateYMD= $dat->Format('d-m-Y');
            $line = 1;
            $cuentasPunto = \App\ADMPUNTOASIENTOS::where('PUNTO',$punto)
            ->where('ANIO',$dat->format('Y'))
            ->get();

            Log::info(["cuentasPunto 1"=>$cuentasPunto]);

            $CLI = "";
            $VE0 = "";
            $VEN = "";
            $IN0 = "";
            $INV = "";
            $CO0 = "";
            $COS = "";
            $DE0 = "";
            $DES = "";
            $IVA = "";

            $cuentasPunto = \App\ADMPUNTOASIENTOS::where('PUNTO','=',$punto)
            ->where('ANIO',Carbon::now()->format('Y'))
            ->where('ASIENTO','=','VEN')
            ->get();

            Log::info(["cuentasPunto 2"=>$cuentasPunto]);

            foreach ($cuentasPunto->toArray() as $k => $v){
                if(in_array('CLI',$v)){
                    $CLI = trim($v['CUENTA']);
                }
                if(in_array('VE0',$v)){
                    $VE0 = trim($v['CUENTA']);
                }
                if(in_array('VEN',$v)){
                    $VEN = trim($v['CUENTA']);
                }
                if(in_array('IN0',$v)){
                    $IN0 = trim($v['CUENTA']);
                }
                if(in_array('INV',$v)){
                    $INV = trim($v['CUENTA']);
                }
                if(in_array('CO0',$v)){
                    $CO0 = trim($v['CUENTA']);
                }
                if(in_array('COS',$v)){
                    $COS = trim($v['CUENTA']);
                }
                if(in_array('DE0',$v)){
                    $DE0 = trim($v['CUENTA']);
                }
                if(in_array('DES',$v)){
                    $DES = trim($v['CUENTA']);
                }
                if(in_array('IVA',$v)){
                    $IVA = trim($v['CUENTA']);
                }
            }

            $cabecera = new \App\ADMCABCOMPROBANTE();
            $cabecera->secuencial = $paramBO->secuencial + 1;
            $cabecera->fecha = $dateYMD;
            $cabecera->tipoComprobante = 18;
            $cabecera->numero = -1;
            $cabecera->cliente = $nombreCli;
            $cabecera->detalle = $observa;
            $cabecera->debito = $neto;
            $cabecera->credito = $neto;
            $cabecera->estado = "C";
            $cabecera->retencion = "N";
            $cabecera->fechao = $dateYMD;
            $cabecera->operador = $opera;
            $cabecera->modulo = "CXC";
            $cabecera->save();
    
            $paramBO->secuencial = $cabecera->secuencial;
            $paramBO->save();
    
            //linea Cliente
            $clienteR = new \App\ADMDETCOMPROBANTE();
            $clienteR->SECUENCIAL = $cabecera->secuencial;
            $clienteR->LINEA = $line;
            $clienteR->CUENTA = $CLI;
            $clienteR->DETALLE = $observa;
            $clienteR->MONTO = $neto;
            $clienteR->ESTADO = "C";
            $clienteR->DBCR = "DB";
            $clienteR->save();
            $line += 1;

            if($iva > 0){
                //linea IVA
                $ivar = new \App\ADMDETCOMPROBANTE();
                $ivar->SECUENCIAL = $cabecera->secuencial;
                $ivar->LINEA = $line;
                $ivar->CUENTA = $IVA;
                $ivar->DETALLE = $observa;
                $ivar->MONTO = $iva;
                $ivar->ESTADO = "C";
                $ivar->DBCR = "CR";
                $ivar->save();
                $line += 1;

                //linea Venta
                $venta = new \App\ADMDETCOMPROBANTE();
                $venta->SECUENCIAL = $cabecera->secuencial;
                $venta->LINEA = $line;
                $venta->CUENTA = $VEN;
                $venta->DETALLE = $observa;
                $venta->MONTO = $subTotal;
                $venta->ESTADO = "C";
                $venta->DBCR = "CR";
                $venta->save();
                $line += 1;

                //linea Inventario
                $inventario = new \App\ADMDETCOMPROBANTE();
                $inventario->SECUENCIAL = $cabecera->secuencial;
                $inventario->LINEA = $line;
                $inventario->CUENTA = $INV;
                $inventario->DETALLE = $observa;
                $inventario->MONTO = $inv;
                $inventario->ESTADO = "C";
                $inventario->DBCR = "CR";
                $inventario->save();
                $line += 1;

                //linea Costo
                $costor = new \App\ADMDETCOMPROBANTE();
                $costor->SECUENCIAL = $cabecera->secuencial;
                $costor->LINEA = $line;
                $costor->CUENTA = $COS;
                $costor->DETALLE = $observa;
                $costor->MONTO = $cost;
                $costor->ESTADO = "C";
                $costor->DBCR = "DB";
                $costor->save();
                $line += 1;

                if($desc > 0){
                    //linea descuento
                    $descuentor = new \App\ADMDETCOMPROBANTE();
                    $descuentor->SECUENCIAL = $cabecera->secuencial;
                    $descuentor->LINEA = $line;
                    $descuentor->CUENTA = $DES;
                    $descuentor->DETALLE = $observa;
                    $descuentor->MONTO = $desc;
                    $descuentor->ESTADO = "C";
                    $descuentor->DBCR = "DB";
                    $descuentor->save();
                    $line += 1;
                }


                if($subTotal0 > 0){
                    
                    //linea venta0
                    $venta0 = new \App\ADMDETCOMPROBANTE();
                    $venta0->SECUENCIAL = $cabecera->secuencial;
                    $venta0->LINEA = $line;
                    $venta0->CUENTA = $VE0;
                    $venta0->DETALLE = $observa;
                    $venta0->MONTO = $subTotal0;
                    $venta0->ESTADO = "C";
                    $venta0->DBCR = "CR";
                    $venta0->save();
                    $line += 1;
                    
                    //linea inventario0    
                    $inventario0 = new \App\ADMDETCOMPROBANTE();
                    $inventario0->SECUENCIAL = $cabecera->secuencial;
                    $inventario0->LINEA = $line;
                    $inventario0->CUENTA = $IN0;
                    $inventario0->DETALLE = $observa;
                    $inventario0->MONTO = $inv0;
                    $inventario0->ESTADO = "C";
                    $inventario0->DBCR = "CR";
                    $inventario0->save();
                    $line += 1;

                    //linea Costo 0
                    $costor0 = new \App\ADMDETCOMPROBANTE();
                    $costor0->SECUENCIAL = $cabecera->secuencial;
                    $costor0->LINEA = $line;
                    $costor0->CUENTA = $CO0;
                    $costor0->DETALLE = $observa;
                    $costor0->MONTO = $cost0;
                    $costor0->ESTADO = "C";
                    $costor0->DBCR = "DB";
                    $costor0->save();
                    $line += 1;
                    
                    if($desc0 > 0){
                        //linea descuento 0
                        $descuentor0 = new \App\ADMDETCOMPROBANTE();
                        $descuentor0->SECUENCIAL = $cabecera->secuencial;
                        $descuentor0->LINEA = $line;
                        $descuentor0->CUENTA = $DE0;
                        $descuentor0->DETALLE = $observa;
                        $descuentor0->MONTO = $desc0;
                        $descuentor0->ESTADO = "C";
                        $descuentor0->DBCR = "DB";
                        $descuentor0->save();
                        $line += 1;
                    }
                }
            }else{

                //linea Venta0
                $venta0 = new \App\ADMDETCOMPROBANTE();
                $venta0->SECUENCIAL = $cabecera->secuencial;
                $venta0->LINEA = $line;
                $venta0->CUENTA = $VE0;
                $venta0->DETALLE = $observa;
                $venta0->MONTO = $subTotal;
                $venta0->ESTADO = "C";
                $venta0->DBCR = "CR";
                $venta0->save();
                $line += 1;
 
                //linea Inventario0
                $inventario0 = new \App\ADMDETCOMPROBANTE();
                $inventario0->SECUENCIAL = $cabecera->secuencial;
                $inventario0->LINEA = $line;
                $inventario0->CUENTA = $IN0;
                $inventario0->DETALLE = $observa;
                $inventario0->MONTO = $inv0;
                $inventario0->ESTADO = "C";
                $inventario0->DBCR = "CR";
                $inventario0->save();
                $line += 1;
 
                //linea Costo0
                $costor0 = new \App\ADMDETCOMPROBANTE();
                $costor0->SECUENCIAL = $cabecera->secuencial;
                $costor0->LINEA = $line;
                $costor0->CUENTA = $CO0;
                $costor0->DETALLE = $observa;
                $costor0->MONTO = $cost0;
                $costor0->ESTADO = "C";
                $costor0->DBCR = "DB";
                $costor0->save();
                $line += 1;
 
                if($desc0 > 0){
                    //linea descuento0
                    $descuentor0 = new \App\ADMDETCOMPROBANTE();
                    $descuentor0->SECUENCIAL = $cabecera->secuencial;
                    $descuentor0->LINEA = $line;
                    $descuentor0->CUENTA = $DE0;
                    $descuentor0->DETALLE = $observa;
                    $descuentor0->MONTO = $desc0;
                    $descuentor0->ESTADO = "C";
                    $descuentor0->DBCR = "DB";
                    $descuentor0->save();
                    $line += 1;
                }
            }
            return $cabecera->secuencial;
        } catch (\Exception $e) {
            Log::erorr(["Creado asiento"=>$e->getMessage()]);
            DB::rollback();
        }
        
    }

    public function CrearCuotas($meses,$montoDeuda,$numCuotas,$secDeuda,$inicioPago,$tipoPago){
        
        $fechaInicio = Carbon::createFromFormat('d-m-Y',$inicioPago);

        $montoCuotas = round($montoDeuda / $numCuotas,2);
        $sumatoria = $montoCuotas * $numCuotas;
        $diferencia = $montoDeuda - $sumatoria;
        $saldoProgramado =   $montoDeuda;
        
        DB::beginTransaction();
        
        try {
            for ($i=1; $i <= $numCuotas; $i++) { 
            
                $deudaCuota = new \App\ADMDEUDACUOTA();
                $deudaCuota->SECDEUDA = $secDeuda;
                $deudaCuota->NUMCUOTA = $i ;
                $deudaCuota->INTERES = null;
                $deudaCuota->SALDOPROGRAMADO = round($saldoProgramado - $montoCuotas,2);
    
                if($deudaCuota->SALDOPROGRAMADO < 0){
                    $deudaCuota->SALDOPROGRAMADO = 0;
                }
    
                $deudaCuota->CREDITO = 0;
                $deudaCuota->SALDO = round($montoCuotas,2);
                $deudaCuota->MONTO = round($montoCuotas,2);
                
                if($i == $numCuotas){
                    $deudaCuota->MONTO = round($montoCuotas + $diferencia,2);
                    $deudaCuota->SALDO = round($montoCuotas + $diferencia,2);
                }
                
                if($i == 1){                
                    $deudaCuota->FECHAVEN = $fechaInicio->Format('Y-d-m');
                }else{
                    if($tipoPago == 'M'){
                        $deudaCuota->FECHAVEN = $fechaInicio->addMonth()->Format('Y-d-m');
                    }elseif($tipoPago == 'D'){
                        $deudaCuota->FECHAVEN = $fechaInicio->addDay()->Format('Y-d-m');
                    }elseif($tipoPago == 'Q'){
                        $deudaCuota->FECHAVEN = $fechaInicio->addDay(15)->Format('Y-d-m');
                    }                    
                }
                
                $deudaCuota->FECHACANCELACUOTA = null;
                $deudaCuota->INTERESACUMORA = 0;
                $deudaCuota->INTERESPAGADO = 0;
                $deudaCuota->OBSERVACION = 'Lrvl.';
                $deudaCuota->NUMPAGO = null;    
                $deudaCuota->save();

                $saldoProgramado = $deudaCuota->SALDOPROGRAMADO;
            }               
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            Log::error("Creando cuotas del credito",['data'=>$th->getMessage()]);
            DB::rollback();
            return false;
        }
    }

    public function CrearNotaDebito($monto,$secuencial,$secinv,$cliente,$observa,$vende,$ope,$caja,
    $numFac,$bodega,$serie,$fecha,$meses){
            
        $numDB = \App\ADMTIPODOC::where('TIPO','=','NDB')->first();
        $fechaVen = Carbon::createFromFormat('Y-d-m',$fecha);
        Log::info("Creando nota de debito por Entrada,");
        try {
            $deudan = new ADMDEUDA();
            $deudan->SECUENCIAL = $secuencial;
            $deudan->BODEGA = $bodega;
            $deudan->CLIENTE = $cliente;
            $deudan->TIPO = "NDB";
            $deudan->NUMERO = $numDB->CONTADOR + 1;
            Log::info(["contador" =>$numDB->CONTADOR]);
            $deudan->SERIE = $serie;
            $deudan->SECINV = $secinv;
            $deudan->IVA = 0;
            $deudan->MONTO = round($monto,2);
            $deudan->CREDITO = round($monto,2);
            $deudan->SALDO = 0;
            $deudan->FECHAEMI = $fecha;
            $deudan->FECHAVEN = $fechaVen->addMonth($meses)->Format('Y-d-m');
            $deudan->FECHADES = $fecha;
            $deudan->OPERADOR = $ope;
            $deudan->VENDEDOR = $vende;
            $deudan->OBSERVACION =  'ValorEntrada: '.$observa;
            $deudan->NUMAUTO = "";
            $deudan->BODEGAFAC = $bodega;
            $deudan->SERIEFAC = $serie;
            $deudan->NUMEROFAC = $numFac;
            $deudan->ACT_SCT = "N";
            $deudan->montodocumento = 0;
            $deudan->tipoventa = ''; // 'CRE'
            $deudan->mesescredito = 0;
            $deudan->tipopago = "";
            $deudan->numeropagos = 0;
            $deudan->entrada = 0;
            $deudan->valorfinanciado = 0;
            $deudan->porinteres = 0;
            $deudan->montointeres = 0;
            $deudan->totaldeuda = 0;
            $deudan->seccreditogen = 0;
            $deudan->secdeudagen = 0;
            $deudan->numcuotagen = 0;
            $deudan->porinteresmora = 0;
            $deudan->basecalculo = 0;
            $deudan->diasatraso = 0;
            $deudan->usuarioeli = 0;
            $deudan->EWEB = "N";
            $deudan->save();
            
            $creditoNdb = new \App\ADMCREDITO();
            $creditoNdb->SECUENCIAL = $secuencial;
            $creditoNdb->BODEGA = $bodega;
            $creditoNdb->CLIENTE = $cliente;
            $creditoNdb->TIPO = "NDB";
            $creditoNdb->NUMERO = $deudan->NUMERO;
            $creditoNdb->SERIE = $deudan->SERIE;
            $creditoNdb->SECINV = $deudan->SECINV;
            $creditoNdb->FECHA = $deudan->FECHA;
            $creditoNdb->MONTO = round($deudan->MONTO,2) ;
            $creditoNdb->SALDO = round($deudan->MONTO,2) ;
            $creditoNdb->OPERADOR = $ope;
            $creditoNdb->OBSERVACION =  'ValorEntrada: '.$observa;
            $creditoNdb->VENDEDOR = $vende;
            $creditoNdb->estafirmado = "N";
            $creditoNdb->ACT_SCT = "N";
            $creditoNdb->seccreditogen = 0;            
            $creditoNdb->save();

            $result = DB::table('ADMTIPODOC')
                                ->where('TIPO', 'NDB')
                                ->update([
                                    'CONTADOR'=>  $deudan->NUMERO
                                ]);
            return true;
        } catch (\Throwable $th) {
            Log::error("Error creando Nota de Debito por entrada: ".$th->getMessage());
            return false;
        } 
    }
}