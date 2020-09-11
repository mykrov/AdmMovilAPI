<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $secuencial
 * @property float $numeromovimiento
 * @property string $motivo
 * @property float $numdocumento
 * @property string $banco
 * @property string $cuenta
 * @property string $tipomovimiento
 * @property string $fecha
 * @property string $tipodocumento
 * @property string $fechavence
 * @property float $monto
 * @property string $beneficiario
 * @property string $numpapel
 * @property float $seccon
 * @property string $origen
 * @property string $observacion
 * @property string $hora
 * @property string $operador
 * @property string $nombrepc
 * @property int $cajac
 * @property string $estado
 * @property string $opereli
 * @property string $fechaeli
 * @property string $intconcaja
 * @property string $conciliado
 * @property float $SECCONCILIACION
 */
class ADMMOVIBANCOCIA extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMMOVIBANCOCIA';

    /**
     * @var array
     */
    protected $fillable = ['tipomovimiento', 'fecha', 'tipodocumento', 'fechavence', 'monto', 'beneficiario', 'numpapel', 'seccon', 'origen', 'observacion', 'hora', 'operador', 'nombrepc', 'cajac', 'estado', 'opereli', 'fechaeli', 'intconcaja', 'conciliado', 'SECCONCILIACION'];

}
