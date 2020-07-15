<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $secuencial
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
 * @property string $cobrador
 * @property float $serierecibo
 * @property string $fechaeli
 * @property string $horaeli
 * @property string $maquinaeli
 * @property string $operadoreli
 */
class ADMPAGO extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMPAGO';

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
    protected $fillable = ['cliente', 'tipo', 'numero', 'monto', 'operador', 'observacion', 'numpapel', 'fecha', 'integrado', 'seccon', 'vendedor', 'noguia', 'oripago', 'nocarga', 'hora', 'nombrepc', 'cajac', 'cobrador', 'serierecibo', 'fechaeli', 'horaeli', 'maquinaeli', 'operadoreli'];

}
