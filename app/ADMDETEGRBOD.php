<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $SECUENCIAL
 * @property string $ITEM
 * @property float $CANTIU
 * @property float $CANTIC
 * @property float $CANTFUN
 * @property float $COSTOP
 * @property float $COSTOU
 * @property float $INDICE
 * @property string $SERIALITEM
 */
class ADMDETEGRBOD extends Model
{
    
    public $incrementing = false;
    public $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMDETEGRBOD';

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
    protected $fillable = ['SECUENCIAL', 'ITEM', 'CANTIU', 'CANTIC', 'CANTFUN', 'COSTOP', 'COSTOU','SERIALITEM'];

}
