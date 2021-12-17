<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class Item extends Controller
{
    public function ItemXBodega($bodega)
    {
       
        // $data = DB::select(DB::raw("exec SP_ITEMS_APPMOVIL :Param1"),[
        //     ':Param1' => $bodega
        // ]);

        $page = \Request::input('page', 1);  
        $paginate = 200;  
      
        $data = DB::select(DB::raw("exec SP_ITEMS_APPMOVIL :Param1"),[
            ':Param1' => $bodega
        ]);  
      
        $offSet = ($page * $paginate) - $paginate;  
        $itemsForCurrentPage = array_slice($data, $offSet, $paginate, true);  
        $data = new \Illuminate\Pagination\LengthAwarePaginator($itemsForCurrentPage, count($data), $paginate, $page); 

        return response()->json($data);
    }

    public function ItemTodosBodega($bodega)
    {
        $data = DB::select(DB::raw("exec SP_ITEMS_APPMOVIL :Param1"),[
            ':Param1' => $bodega
        ]);
        return response()->json($data);
    }


    public function ItemBodegaXCategoria($bodega,$categoria)
    {
        
        $items2 = DB::select(DB::raw("exec SP_ITEMS_APPMOVIL_CATE :Param1,:Param2"),[
            ':Param1' => $bodega,
            ':Param2' => $categoria
        ]);

        return response()->json($items2);

    }

    public function ItemXCodigo($codigo,$bodega)
    {
        // $item3 = \App\ADMITEM::where('ITEM','like', '%' . $codigo . '%')
        // ->where('ESTADO','=','A')        
        // ->select(['ITEM','NOMBRE','NOMBRECORTO','CATEGORIA','FAMILIA','LINEA','MARCA','PRESENTA','ESTADO','DISPOVEN','IVA','BIEN','PROVEEDOR','FACTOR','STOCK','STOCKMI','STOCKMA','PESO','VOLUMEN','PRECIO1','PRECIO2','PRECIO3','PRECIO4','PRECIO5','PVP','ITEMR','ULTVEN','ULTCOM','COSTOP','COSTOU','OBSERVA','GRUPO','COMBO','REGALO','CODPROV','PORUTI','PORUTIVENTA','CODBARRA','CANFRA','STOCKMAY','PORUTIPRE1','PORUTIPRE2','PORUTIPRE3','PORUTIPRE4','PORUTIPRE5','LITROS','WEB','OFERTA','POFERTA','NOVEDAD','IMAGEN','CANTCOMPRA','SOLOPOS','CUENTAVENTA','ESPT','IMAGENADICIONAL','TIENECTAVENTA','tipoprofal','PORDESSUGERIDO','NUMCOTIZACION','CATEGORIA as NOMBRE_CATEGORIA','CATEGORIA as NOMBRE_FAMILIA','CATEGORIA as NOMBRE_LINEA','CATEGORIA as NOMBRE_MARCA','CATEGORIA as NOMBRE_PRESENTA','CATEGORIA as NOMBRE_PROVEEDOR','CATEGORIA as NUMERO','CATEGORIA as SECUENCIAL','CATEGORIA as BODEGA','CATEGORIA as SUBIDO'])->get();
                
        $items3bod = DB::table('ADMITEM')
        ->where('ADMITEM.ITEM','=', $codigo)
        ->where('ADMITEM.ESTADO','A')
        ->where('ADMITEM.DISPOVEN','S')
        ->join('ADMITEMBOD','ADMITEMBOD.ITEM','=','ADMITEM.ITEM')
        ->where('ADMITEMBOD.BODEGA','=',$bodega)
        ->select (['ADMITEM.ITEM',
        'ADMITEM.NOMBRE',
        'ADMITEM.NOMBRECORTO',
        'ADMITEM.CATEGORIA',
        'ADMITEM.FAMILIA',
        'ADMITEM.LINEA',
        'ADMITEM.MARCA',
        'ADMITEM.PRESENTA',
        'ADMITEM.ESTADO',
        'ADMITEM.DISPOVEN',
        'ADMITEM.IVA',
        'ADMITEM.BIEN',
        'ADMITEM.PROVEEDOR',
        'ADMITEM.FACTOR',
        DB::raw('FLOOR(ADMITEMBOD.STOCK) as STOCK'),//Stock desde Bodega
        'ADMITEM.STOCKMI',
        'ADMITEM.STOCKMA',
        'ADMITEM.PESO',
        'ADMITEM.VOLUMEN',
        'ADMITEM.PRECIO1',
        'ADMITEM.PRECIO2',
        'ADMITEM.PRECIO3',
        'ADMITEM.PRECIO4',
        'ADMITEM.PRECIO5',
        'ADMITEM.PVP',
        'ADMITEM.ITEMR',
        'ADMITEM.ULTVEN',
        'ADMITEM.ULTCOM',
        'ADMITEM.COSTOP',
        'ADMITEM.COSTOU',
        'ADMITEM.OBSERVA',
        'ADMITEM.GRUPO',
        'ADMITEM.COMBO',
        'ADMITEM.REGALO',
        'ADMITEM.CODPROV',
        'ADMITEM.PORUTI',
        'ADMITEM.PORUTIVENTA',
        'ADMITEM.CODBARRA',
        'ADMITEM.CANFRA',
        'ADMITEM.STOCKMAY',
        'ADMITEM.PORUTIPRE1',
        'ADMITEM.PORUTIPRE2',
        'ADMITEM.PORUTIPRE3',
        'ADMITEM.PORUTIPRE4',
        'ADMITEM.PORUTIPRE5',
        'ADMITEM.LITROS',
        'ADMITEM.WEB',
        'ADMITEM.OFERTA',
        'ADMITEM.POFERTA',
        'ADMITEM.NOVEDAD',
        'ADMITEM.IMAGEN',
        'ADMITEM.CANTCOMPRA',
        'ADMITEM.SOLOPOS',
        'ADMITEM.CUENTAVENTA',
        'ADMITEM.ESPT',
        'ADMITEM.IMAGENADICIONAL',
        'ADMITEM.TIENECTAVENTA',
        'ADMITEM.tipoprofal',
        'ADMITEM.PORDESSUGERIDO',
        'ADMITEM.NUMCOTIZACION',
        'ADMITEM.CATEGORIA as NOMBRE_CATEGORIA',
        'ADMITEM.CATEGORIA as NOMBRE_FAMILIA',
        'ADMITEM.CATEGORIA as NOMBRE_LINEA',
        'ADMITEM.CATEGORIA as NOMBRE_MARCA',
        'ADMITEM.CATEGORIA as NOMBRE_PRESENTA',
        'ADMITEM.CATEGORIA as NOMBRE_PROVEEDOR',
        'ADMITEM.CATEGORIA as NUMERO',
        'ADMITEM.CATEGORIA as SECUENCIAL',
        'ADMITEM.CATEGORIA as BODEGA',
        'ADMITEM.CATEGORIA as SUBIDO'])
        ->get();

        return response()->json($items3bod);
    }
   

    public function ItemsXNombre($like,$bodega)
    {
        // $items4 = \App\ADMITEM::where('NOMBRE','like', '%' . $like . '%')
        // ->where('ESTADO','=','A')
        // ->select(['ITEM','NOMBRE','NOMBRECORTO','CATEGORIA','FAMILIA','LINEA','MARCA','PRESENTA','ESTADO','DISPOVEN','IVA','BIEN','PROVEEDOR','FACTOR','STOCK','STOCKMI','STOCKMA','PESO','VOLUMEN','PRECIO1','PRECIO2','PRECIO3','PRECIO4','PRECIO5','PVP','ITEMR','ULTVEN','ULTCOM','COSTOP','COSTOU','OBSERVA','GRUPO','COMBO','REGALO','CODPROV','PORUTI','PORUTIVENTA','CODBARRA','CANFRA','STOCKMAY','PORUTIPRE1','PORUTIPRE2','PORUTIPRE3','PORUTIPRE4','PORUTIPRE5','LITROS','WEB','OFERTA','POFERTA','NOVEDAD','IMAGEN','CANTCOMPRA','SOLOPOS','CUENTAVENTA','ESPT','IMAGENADICIONAL','TIENECTAVENTA','tipoprofal','PORDESSUGERIDO','NUMCOTIZACION','CATEGORIA as NOMBRE_CATEGORIA','CATEGORIA as NOMBRE_FAMILIA','CATEGORIA as NOMBRE_LINEA','CATEGORIA as NOMBRE_MARCA','CATEGORIA as NOMBRE_PRESENTA','CATEGORIA as NOMBRE_PROVEEDOR','CATEGORIA as NUMERO','CATEGORIA as SECUENCIAL','CATEGORIA as BODEGA','CATEGORIA as SUBIDO'])->get();
        $items3bod = DB::table('ADMITEM')
        ->where('ADMITEM.NOMBRE','like', '%' . $like . '%')
        ->where('ADMITEM.ESTADO','A')
        ->where('ADMITEM.DISPOVEN','S')
        ->join('ADMITEMBOD','ADMITEMBOD.ITEM','=','ADMITEM.ITEM')
        ->where('ADMITEMBOD.BODEGA','=',$bodega)
        ->select (['ADMITEM.ITEM',
        'ADMITEM.NOMBRE',
        'ADMITEM.NOMBRECORTO',
        'ADMITEM.CATEGORIA',
        'ADMITEM.FAMILIA',
        'ADMITEM.LINEA',
        'ADMITEM.MARCA',
        'ADMITEM.PRESENTA',
        'ADMITEM.ESTADO',
        'ADMITEM.DISPOVEN',
        'ADMITEM.IVA',
        'ADMITEM.BIEN',
        'ADMITEM.PROVEEDOR',
        'ADMITEM.FACTOR',
        DB::raw('FLOOR(ADMITEMBOD.STOCK) as STOCK'),//Stock desde Bodega
        'ADMITEM.STOCKMI',
        'ADMITEM.STOCKMA',
        'ADMITEM.PESO',
        'ADMITEM.VOLUMEN',
        'ADMITEM.PRECIO1',
        'ADMITEM.PRECIO2',
        'ADMITEM.PRECIO3',
        'ADMITEM.PRECIO4',
        'ADMITEM.PRECIO5',
        'ADMITEM.PVP',
        'ADMITEM.ITEMR',
        'ADMITEM.ULTVEN',
        'ADMITEM.ULTCOM',
        'ADMITEM.COSTOP',
        'ADMITEM.COSTOU',
        'ADMITEM.OBSERVA',
        'ADMITEM.GRUPO',
        'ADMITEM.COMBO',
        'ADMITEM.REGALO',
        'ADMITEM.CODPROV',
        'ADMITEM.PORUTI',
        'ADMITEM.PORUTIVENTA',
        'ADMITEM.CODBARRA',
        'ADMITEM.CANFRA',
        'ADMITEM.STOCKMAY',
        'ADMITEM.PORUTIPRE1',
        'ADMITEM.PORUTIPRE2',
        'ADMITEM.PORUTIPRE3',
        'ADMITEM.PORUTIPRE4',
        'ADMITEM.PORUTIPRE5',
        'ADMITEM.LITROS',
        'ADMITEM.WEB',
        'ADMITEM.OFERTA',
        'ADMITEM.POFERTA',
        'ADMITEM.NOVEDAD',
        'ADMITEM.IMAGEN',
        'ADMITEM.CANTCOMPRA',
        'ADMITEM.SOLOPOS',
        'ADMITEM.CUENTAVENTA',
        'ADMITEM.ESPT',
        'ADMITEM.IMAGENADICIONAL',
        'ADMITEM.TIENECTAVENTA',
        'ADMITEM.tipoprofal',
        'ADMITEM.PORDESSUGERIDO',
        'ADMITEM.NUMCOTIZACION',
        'ADMITEM.CATEGORIA as NOMBRE_CATEGORIA',
        'ADMITEM.CATEGORIA as NOMBRE_FAMILIA',
        'ADMITEM.CATEGORIA as NOMBRE_LINEA',
        'ADMITEM.CATEGORIA as NOMBRE_MARCA',
        'ADMITEM.CATEGORIA as NOMBRE_PRESENTA',
        'ADMITEM.CATEGORIA as NOMBRE_PROVEEDOR',
        'ADMITEM.CATEGORIA as NUMERO',
        'ADMITEM.CATEGORIA as SECUENCIAL',
        'ADMITEM.CATEGORIA as BODEGA',
        'ADMITEM.CATEGORIA as SUBIDO'])
        ->get();

        return response()->json($items3bod);
        
    }

    public function ItemsSinStock($bodega)
    {       
        $page = \Request::input('page', 1);  
        $paginate = 200;  
      
        $data = DB::select(DB::raw("exec SP_ITEMS_APP_NOSTOCK :Param1"),[
            ':Param1' => $bodega
        ]);  
      
        $offSet = ($page * $paginate) - $paginate;  
        $itemsForCurrentPage = array_slice($data, $offSet, $paginate, true);  
        $data = new \Illuminate\Pagination\LengthAwarePaginator($itemsForCurrentPage, count($data), $paginate, $page); 

        return response()->json($data);
    }

    public function itemsDeRegalo() {
        $items = \App\ADMITEM::where('REGALO','=','S')
        ->get();
        return response()->json($items);
    }


    public function ItemBarCode(Request $r){
        
        $barr = trim($r['codigo']);
        $code = \App\ADMITEM::where('CODBARRA',$barr)
        ->select('ITEM','NOMBRE','IMAGENADICIONAL','STOCK','PRECIO1','FACTOR')
        ->get();

        return response()->json($code);

    }
}
