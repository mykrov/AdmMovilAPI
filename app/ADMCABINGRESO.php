<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $TIPO
 * @property int $BODEGA
 * @property float $NUMERO
 * @property string $SERIE
 * @property float $SECUENCIAL
 * @property float $NUMSECCOMPRA
 * @property string $DOCREL
 * @property float $NUMEROREL
 * @property string $SERIEREL
 * @property string $FECHA
 * @property string $OPERADOR
 * @property string $CLIENTE
 * @property string $VENDEDOR
 * @property string $PROVEEDOR
 * @property float $SUBTOTAL0
 * @property float $SUBTOTAL
 * @property float $DESCUENTO
 * @property float $IVA
 * @property float $NETO
 * @property float $TRANSPORTE
 * @property float $RECARGO
 * @property float $PESO
 * @property string $MOTIVO
 * @property string $OBSERVA
 * @property string $COMENTA
 * @property string $INTECXC
 * @property string $ESTADO
 * @property string $REGISTRADO
 * @property string $INTEGRADO
 * @property float $SECCON
 * @property string $DEUDA
 * @property float $NOFAC
 * @property string $RETFUE
 * @property string $FECHAEMI
 * @property string $APLSRI
 * @property string $NUMAUTO
 * @property string $HORA
 * @property string $NOMBREPC
 * @property float $IVAA
 * @property string $claseAjuIngreso
 * @property string $ESTADODOC
 * @property string $FECHACADUCA
 * @property string $GRAVAIVA
 * @property string $CAMION
 * @property float $SECAUTOVENTA
 * @property string $CTARCRE
 * @property string $TIPOCLIENTE
 * @property string $ACT_SCT
 * @property int $NUMPRODUCCION
 * @property int $BODEGAORI
 * @property string $tipodoccom
 * @property string $SUBIDO
 * @property string $ENVIADONESTLE
 * @property int $cajac
 * @property string $modulo
 * @property float $valorapagar
 * @property string $ESTADOTRA
 * @property string $OPERAAPRUEBA
 * @property string $FECHAAPRUEBA
 * @property string $MAQUINAAPRUEBA
 * @property string $HORAAPRUEBA
 * @property integer $NUMPESAJE
 * @property string $ESTADOLIQ
 * @property float $SECORDENTRABAJO
 * @property ADMDETINGRESO[] $aDMDETINGRESOs
 */
class ADMCABINGRESO extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMCABINGRESO';

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
    protected $fillable = ['TIPO', 'BODEGA', 'NUMERO', 'SERIE', 'NUMSECCOMPRA', 'DOCREL', 'NUMEROREL', 'SERIEREL', 'FECHA', 'OPERADOR', 'CLIENTE', 'VENDEDOR', 'PROVEEDOR', 'SUBTOTAL0', 'SUBTOTAL', 'DESCUENTO', 'IVA', 'NETO', 'TRANSPORTE', 'RECARGO', 'PESO', 'MOTIVO', 'OBSERVA', 'COMENTA', 'INTECXC', 'ESTADO', 'REGISTRADO', 'INTEGRADO', 'SECCON', 'DEUDA', 'NOFAC', 'RETFUE', 'FECHAEMI', 'APLSRI', 'NUMAUTO', 'HORA', 'NOMBREPC', 'IVAA', 'claseAjuIngreso', 'ESTADODOC', 'FECHACADUCA', 'GRAVAIVA', 'CAMION', 'SECAUTOVENTA', 'CTARCRE', 'TIPOCLIENTE', 'ACT_SCT', 'NUMPRODUCCION', 'BODEGAORI', 'tipodoccom', 'SUBIDO', 'ENVIADONESTLE', 'cajac', 'modulo', 'valorapagar', 'ESTADOTRA', 'OPERAAPRUEBA', 'FECHAAPRUEBA', 'MAQUINAAPRUEBA', 'HORAAPRUEBA', 'NUMPESAJE', 'ESTADOLIQ', 'SECORDENTRABAJO'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function aDMDETINGRESOs()
    {
        return $this->hasMany('App\ADMDETINGRESO', 'SECUENCIAL', 'SECUENCIAL');
    }
}
