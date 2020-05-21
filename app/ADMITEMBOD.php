<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $BODEGA
 * @property string $ITEM
 * @property float $STOCK
 * @property string $ULTFECING
 * @property string $ULTFECEGR
 */
class ADMITEMBOD extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMITEMBOD';

    /**
     * @var array
     */
    protected $fillable = ['STOCK', 'ULTFECING', 'ULTFECEGR'];

}
