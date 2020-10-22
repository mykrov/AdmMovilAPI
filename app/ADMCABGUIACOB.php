<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $NUMGUIA
 * @property string $FECHA
 * @property string $ESTADO
 * @property float $MONTO
 * @property float $SALDO
 * @property string $FECHALIQ
 * @property string $OBSERVA
 * @property string $OPERADOR
 * @property string $HORA
 * @property string $MAQUINA
 * @property float $NRECIBO
 * @property float $RDESDE
 * @property float $RHASTA
 * @property float $BUNO
 * @property float $BDOS
 * @property float $BCINCO
 * @property float $BDIEZ
 * @property float $BVEINTE
 * @property float $BCINCUENTA
 * @property float $BCIEN
 * @property float $BOTROS
 * @property float $MUNOCENTAVO
 * @property float $MCINCO
 * @property float $MDIEZ
 * @property float $MVEINTEYCINCO
 * @property float $MCINCUENTA
 * @property float $MUNO
 * @property float $MOTROS
 * @property string $OBSERVABILLETE
 */
class ADMCABGUIACOB extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMCABGUIACOB';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'NUMGUIA';

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
    protected $fillable = ['FECHA', 'ESTADO', 'MONTO', 'SALDO', 'FECHALIQ', 'OBSERVA', 'OPERADOR', 'HORA', 'MAQUINA', 'NRECIBO', 'RDESDE', 'RHASTA', 'BUNO', 'BDOS', 'BCINCO', 'BDIEZ', 'BVEINTE', 'BCINCUENTA', 'BCIEN', 'BOTROS', 'MUNOCENTAVO', 'MCINCO', 'MDIEZ', 'MVEINTEYCINCO', 'MCINCUENTA', 'MUNO', 'MOTROS', 'OBSERVABILLETE'];

}
