<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $secuencial
 * @property integer $CODIGOCAJA
 * @property int $NUMEROAPERTURA
 * @property string $cliente
 * @property string $tipo
 * @property float $numero
 * @property float $monto
 * @property string $operador
 * @property string $observacion
 * @property string $numpapel
 * @property string $fecha
 * @property string $integrado
 * @property float $seccon
 * @property string $vendedor
 * @property float $noguia
 * @property string $oripago
 * @property float $nocarga
 * @property string $hora
 * @property string $nombrepc
 * @property int $cajac
 * @property float $SECFACTURA
 * @property string $ESTADO
 * @property string $cobrador
 * @property string $serierecibo
 * @property int $empsucursal
 * @property string $operadoreli
 * @property string $maquinaeli
 * @property string $fechaeli
 * @property string $horaeli
 */
class ADMPAGOPOS extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMPAGOPOS';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'secuencial';

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
    protected $fillable = ['CODIGOCAJA', 'NUMEROAPERTURA', 'cliente', 'tipo', 'numero', 'monto', 'operador', 'observacion', 'numpapel', 'fecha', 'integrado', 'seccon', 'vendedor', 'noguia', 'oripago', 'nocarga', 'hora', 'nombrepc', 'cajac', 'SECFACTURA', 'ESTADO', 'cobrador', 'serierecibo', 'empsucursal', 'operadoreli', 'maquinaeli', 'fechaeli', 'horaeli'];

}
