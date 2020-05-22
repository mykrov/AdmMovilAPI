<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $SECUENCIAL
 * @property int $BODEGA
 * @property string $TIPO
 * @property float $NUMERO
 * @property float $NUMEGRESO
 * @property string $FECHA
 * @property string $ESTADO
 */
class ADMCABEGRBOD extends Model
{
  
    protected $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMCABEGRBOD';

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
    protected $fillable = ['BODEGA', 'TIPO', 'NUMERO', 'NUMEGRESO', 'FECHA', 'ESTADO'];

}
