<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $NUMERO
 * @property int $LINEA
 * @property string $ITEM
 * @property float $CANTC
 * @property float $CANTU
 * @property float $CANTRET
 * @property float $CANTAPR
 * @property float $CANTCAPR
 * @property float $CANTUAPR
 * @property string $MOTIVO
 */
class ADMDETRETIRO extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMDETRETIRO';

    /**
     * @var array
     */
    protected $fillable = ['NUMERO', 'LINEA', 'ITEM', 'CANTC', 'CANTU', 'CANTRET', 'CANTAPR', 'CANTCAPR', 'CANTUAPR', 'MOTIVO'];

}
