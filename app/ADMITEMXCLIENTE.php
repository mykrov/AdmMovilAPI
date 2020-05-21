<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $CLIENTE
 * @property string $ITEM
 * @property float $PRECIO
 * @property string $FECHADESDE
 * @property string $FECHAHASTA
 */
class ADMITEMXCLIENTE extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMITEMXCLIENTE';

    /**
     * @var array
     */
    protected $fillable = ['CLIENTE', 'ITEM', 'PRECIO', 'FECHADESDE', 'FECHAHASTA'];

}
