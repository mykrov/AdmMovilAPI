<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $CODIGOCAJA
 * @property int $NUMEROAPERTURA
 * @property float $SECUENCIAL
 * @property string $TIPO
 * @property integer $BODEGA
 * @property float $NUMERO
 * @property string $SERIE
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
 * @property float $EFECTIVO
 * @property float $CAMBIO
 * @property string $TIPOREGISTRO
 * @property string $LLEVASECUENCIA
 * @property integer $PRECIOAPLICADO
 * @property string $tarjetacredito
 * @property string $num_tarjetacredito
 * @property string $BANCO
 * @property string $CUENTA
 * @property string $NUMCHEQUE
 * @property string $NUMEROFACRECETA
 * @property string $NOMBREDOCRECETA
 * @property string $ACT_SCT
 * @property int $numordenlab
 * @property int $SECUENCIALCONTRATO
 * @property string $admventa
 * @property string $fechaventa
 * @property string $horaventa
 * @property float $numeroventa
 * @property string $adm_venta
 * @property string $num_venta
 * @property ADMDETEGRESOPO[] $aDMDETEGRESOPOSs
 */
class ADMCABEGRESOPOS extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMCABEGRESOPOS';

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
    protected $fillable = ['CODIGOCAJA', 'NUMEROAPERTURA', 'TIPO', 'BODEGA', 'NUMERO', 'SERIE', 'NUMPROCESO', 'NUMPEDIDO', 'NUMGUIA', 'CAMION', 'CHOFER', 'DOCREL', 'NUMEROREL', 'FECHA', 'FECHAVEN', 'FECHADES', 'OPERADOR', 'CLIENTE', 'VENDEDOR', 'PROVEEDOR', 'SUBTOTAL', 'DESCUENTO', 'IVA', 'NETO', 'TRANSPORTE', 'RECARGO', 'BODEGADES', 'PESO', 'VOLUMEN', 'MOTIVO', 'ESTADO', 'ESTADODOC', 'TIPOVTA', 'INTECXC', 'OBSERVA', 'COMENTA', 'INTEGRADO', 'SECCON', 'NUMSERIE', 'NOCARGA', 'APLSRI', 'NUMAUTO', 'NUMFISICO', 'HORA', 'NOMBREPC', 'claseAjuEgreso', 'SUBTOTAL0', 'NUMGUIATRANS', 'GRAVAIVA', 'EFECTIVO', 'CAMBIO', 'TIPOREGISTRO', 'LLEVASECUENCIA', 'PRECIOAPLICADO', 'tarjetacredito', 'num_tarjetacredito', 'BANCO', 'CUENTA', 'NUMCHEQUE', 'NUMEROFACRECETA', 'NOMBREDOCRECETA', 'ACT_SCT', 'numordenlab', 'SECUENCIALCONTRATO', 'admventa', 'fechaventa', 'horaventa', 'numeroventa', 'adm_venta', 'num_venta'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function aDMDETEGRESOPOSs()
    {
        return $this->hasMany('App\ADMDETEGRESOPO', 'SECUENCIAL', 'SECUENCIAL');
    }
}
