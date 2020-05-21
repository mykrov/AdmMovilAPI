<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $TIPO
 * @property int $BODEGA
 * @property float $NUMERO
 * @property string $SERIE
 * @property float $SECUENCIAL
 * @property float $NUMPROCESO
 * @property float $NUMPEDIDO
 * @property float $NUMGUIA
 * @property string $CAMION
 * @property string $CHOFER
 * @property string $DOCREL
 * @property float $NUMEROREL
 * @property string $FECHA
 * @property string $FECHAVEN
 * @property string $FECHADES
 * @property string $OPERADOR
 * @property string $CLIENTE
 * @property string $VENDEDOR
 * @property string $PROVEEDOR
 * @property float $SUBTOTAL
 * @property float $DESCUENTO
 * @property float $IVA
 * @property float $NETO
 * @property float $TRANSPORTE
 * @property float $RECARGO
 * @property int $BODEGADES
 * @property float $PESO
 * @property float $VOLUMEN
 * @property string $MOTIVO
 * @property string $ESTADO
 * @property string $ESTADODOC
 * @property string $TIPOVTA
 * @property string $INTECXC
 * @property string $OBSERVA
 * @property string $COMENTA
 * @property string $INTEGRADO
 * @property float $SECCON
 * @property string $NUMSERIE
 * @property float $NOCARGA
 * @property string $APLSRI
 * @property string $NUMAUTO
 * @property string $NUMFISICO
 * @property string $HORA
 * @property string $NOMBREPC
 * @property string $claseAjuEgreso
 * @property float $SUBTOTAL0
 * @property float $NUMGUIATRANS
 * @property string $GRAVAIVA
 * @property string $CREDITO
 * @property string $ESTADODESPACHO
 * @property float $SECAUTOVENTA
 * @property integer $NUMCUOTAS
 * @property float $NUMGUIAREMISION
 * @property float $SBTBIENES
 * @property float $SBTSERVICIOS
 * @property string $TIPOCLIENTE
 * @property string $SUCURSAL
 * @property string $ACT_SCT
 * @property int $NUMPRODUCCION
 * @property string $porentregar
 * @property string $ENTREGADA
 * @property string $REFERENCIA
 * @property string $FECHA_EMBARQUE
 * @property string $BUQUE
 * @property string $NAVIERA
 * @property string $ALMACENARA
 * @property string $PRODUCTO
 * @property string $CODIGORETAILPRO
 * @property string $GRMFACELEC
 * @property string $NOAUTOGRM
 * @property int $mescredito
 * @property string $tipopago
 * @property int $numeropagos
 * @property float $entrada
 * @property float $valorfinanciado
 * @property float $porinteres
 * @property float $montointeres
 * @property float $totaldeuda
 * @property float $xsubtotal
 * @property float $xsubtotal0
 * @property float $xdescuento
 * @property float $xdescuento0
 * @property float $xiva
 * @property float $numerosolicitud
 * @property int $mesescredito
 * @property string $ENVIADONESTLE
 * @property string $tipotienda
 * @property string $CodShip
 * @property integer $NUMPESAJE
 * @property string $ESFOMENTO
 * @property ADMDETEGRESO[] $aDMDETEGRESOs
 */
class ADMCABEGRESO extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMCABEGRESO';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'SECUENCIAL';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'float';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['TIPO', 'BODEGA', 'NUMERO', 'SERIE', 'NUMPROCESO', 'NUMPEDIDO', 'NUMGUIA', 'CAMION', 'CHOFER', 'DOCREL', 'NUMEROREL', 'FECHA', 'FECHAVEN', 'FECHADES', 'OPERADOR', 'CLIENTE', 'VENDEDOR', 'PROVEEDOR', 'SUBTOTAL', 'DESCUENTO', 'IVA', 'NETO', 'TRANSPORTE', 'RECARGO', 'BODEGADES', 'PESO', 'VOLUMEN', 'MOTIVO', 'ESTADO', 'ESTADODOC', 'TIPOVTA', 'INTECXC', 'OBSERVA', 'COMENTA', 'INTEGRADO', 'SECCON', 'NUMSERIE', 'NOCARGA', 'APLSRI', 'NUMAUTO', 'NUMFISICO', 'HORA', 'NOMBREPC', 'claseAjuEgreso', 'SUBTOTAL0', 'NUMGUIATRANS', 'GRAVAIVA', 'CREDITO', 'ESTADODESPACHO', 'SECAUTOVENTA', 'NUMCUOTAS', 'NUMGUIAREMISION', 'SBTBIENES', 'SBTSERVICIOS', 'TIPOCLIENTE', 'SUCURSAL', 'ACT_SCT', 'NUMPRODUCCION', 'porentregar', 'ENTREGADA', 'REFERENCIA', 'FECHA_EMBARQUE', 'BUQUE', 'NAVIERA', 'ALMACENARA', 'PRODUCTO', 'CODIGORETAILPRO', 'GRMFACELEC', 'NOAUTOGRM', 'mescredito', 'tipopago', 'numeropagos', 'entrada', 'valorfinanciado', 'porinteres', 'montointeres', 'totaldeuda', 'xsubtotal', 'xsubtotal0', 'xdescuento', 'xdescuento0', 'xiva', 'numerosolicitud', 'mesescredito', 'ENVIADONESTLE', 'tipotienda', 'CodShip', 'NUMPESAJE', 'ESFOMENTO'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function aDMDETEGRESOs()
    {
        return $this->hasMany('App\ADMDETEGRESO', 'SECUENCIAL', 'SECUENCIAL');
    }
}
