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

    public function ItemXCodigo($codigo)
    {
        $item3 = \App\ADMITEM::where('ITEM','like', '%' . $codigo . '%')
        ->select(['ITEM','NOMBRE','NOMBRECORTO','CATEGORIA','FAMILIA','LINEA','MARCA','PRESENTA','ESTADO','DISPOVEN','IVA','BIEN','PROVEEDOR','FACTOR','STOCK','STOCKMI','STOCKMA','PESO','VOLUMEN','PRECIO1','PRECIO2','PRECIO3','PRECIO4','PRECIO5','PVP','ITEMR','ULTVEN','ULTCOM','COSTOP','COSTOU','OBSERVA','GRUPO','COMBO','REGALO','CODPROV','PORUTI','PORUTIVENTA','CODBARRA','CANFRA','STOCKMAY','PORUTIPRE1','PORUTIPRE2','PORUTIPRE3','PORUTIPRE4','PORUTIPRE5','LITROS','WEB','OFERTA','POFERTA','NOVEDAD','IMAGEN','CANTCOMPRA','SOLOPOS','CUENTAVENTA','ESPT','IMAGENADICIONAL','TIENECTAVENTA','tipoprofal','PORDESSUGERIDO','NUMCOTIZACION','CATEGORIA as NOMBRE_CATEGORIA','CATEGORIA as NOMBRE_FAMILIA','CATEGORIA as NOMBRE_LINEA','CATEGORIA as NOMBRE_MARCA','CATEGORIA as NOMBRE_PRESENTA','CATEGORIA as NOMBRE_PROVEEDOR','CATEGORIA as NUMERO','CATEGORIA as SECUENCIAL','CATEGORIA as BODEGA','CATEGORIA as SUBIDO'])->get();
       
        return response()->json($item3);
    }


    public function ItemsXNombre($like)
    {
        $items4 = \App\ADMITEM::where('NOMBRE','like', '%' . $like . '%')
        ->select(['ITEM','NOMBRE','NOMBRECORTO','CATEGORIA','FAMILIA','LINEA','MARCA','PRESENTA','ESTADO','DISPOVEN','IVA','BIEN','PROVEEDOR','FACTOR','STOCK','STOCKMI','STOCKMA','PESO','VOLUMEN','PRECIO1','PRECIO2','PRECIO3','PRECIO4','PRECIO5','PVP','ITEMR','ULTVEN','ULTCOM','COSTOP','COSTOU','OBSERVA','GRUPO','COMBO','REGALO','CODPROV','PORUTI','PORUTIVENTA','CODBARRA','CANFRA','STOCKMAY','PORUTIPRE1','PORUTIPRE2','PORUTIPRE3','PORUTIPRE4','PORUTIPRE5','LITROS','WEB','OFERTA','POFERTA','NOVEDAD','IMAGEN','CANTCOMPRA','SOLOPOS','CUENTAVENTA','ESPT','IMAGENADICIONAL','TIENECTAVENTA','tipoprofal','PORDESSUGERIDO','NUMCOTIZACION','CATEGORIA as NOMBRE_CATEGORIA','CATEGORIA as NOMBRE_FAMILIA','CATEGORIA as NOMBRE_LINEA','CATEGORIA as NOMBRE_MARCA','CATEGORIA as NOMBRE_PRESENTA','CATEGORIA as NOMBRE_PROVEEDOR','CATEGORIA as NUMERO','CATEGORIA as SECUENCIAL','CATEGORIA as BODEGA','CATEGORIA as SUBIDO'])->get();
       
        return response()->json($items4);
    }

    public function ItemsSinStock()
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
}
