<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $TIPO
 * @property int $BODEGA
 * @property float $NUMERO
 * @property float $NUMPROCESO
 * @property float $SECUENCIAL
 * @property float $NUMINGRESO
 * @property string $ESTADO
 * @property string $FECHA
 * @property ADMDETINGBODEGA[] $aDMDETINGBODEGAs
 */
class ADMCABINGBODEGA extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMCABINGBODEGA';

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
    protected $fillable = ['TIPO', 'BODEGA', 'NUMERO', 'NUMPROCESO', 'NUMINGRESO', 'ESTADO', 'FECHA'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function aDMDETINGBODEGAs()
    {
        return $this->hasMany('App\ADMDETINGBODEGA', 'SECUENCIAL', 'SECUENCIAL');
    }
}
