<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $PRECIO
 * @property string $TIPO
 * @property string $FECHA
 * @property string $OPERADOR
 * @property int $ORDEN
 */
class ADMCLIPRECIO extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMCLIPRECIO';

    /**
     * @var array
     */
    protected $fillable = ['PRECIO', 'TIPO', 'FECHA', 'OPERADOR', 'ORDEN'];

}
