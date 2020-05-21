<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $CODIGO
 * @property string $NOMBRE
 * @property int $DIA
 * @property string $domi
 * @property int $NUMEROCUOTAS
 * @property float $FRECUENCIA
 * @property float $fpagos
 */
class ADMDIASCREDITO extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMDIASCREDITO';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'CODIGO';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['NOMBRE', 'DIA', 'domi', 'NUMEROCUOTAS', 'FRECUENCIA', 'fpagos'];

}
