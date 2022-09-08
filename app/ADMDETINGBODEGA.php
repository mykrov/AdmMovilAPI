<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $SECUENCIAL
 * @property string $ITEM
 * @property float $CANTIU
 * @property float $CANTIC
 * @property float $CANTFUN
 * @property float $COSTO
 * @property float $INDICE
 * @property int $linea
 * @property ADMCABINGBODEGA $aDMCABINGBODEGA
 */
class ADMDETINGBODEGA extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMDETINGBODEGA';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'INDICE';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'float';

    /**
     * @var array
     */
    protected $fillable = ['SECUENCIAL', 'ITEM', 'CANTIU', 'CANTIC', 'CANTFUN', 'COSTO', 'linea'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function aDMCABINGBODEGA()
    {
        return $this->belongsTo('App\ADMCABINGBODEGA', 'SECUENCIAL', 'SECUENCIAL');
    }
}
