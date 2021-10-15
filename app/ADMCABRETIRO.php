<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $NUMERO
 * @property string $CLIENTE
 * @property string $VENDEDOR
 * @property string $FECHA
 * @property string $OBSERVACION
 * @property string $OPERADOR
 * @property string $HORA
 * @property string $NOMBREPC
 * @property string $ESTADO
 */
class ADMCABRETIRO extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMCABRETIRO';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'NUMERO';

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
    protected $fillable = ['CLIENTE', 'VENDEDOR', 'FECHA', 'OBSERVACION', 'OPERADOR', 'HORA', 'NOMBREPC', 'ESTADO'];

}
