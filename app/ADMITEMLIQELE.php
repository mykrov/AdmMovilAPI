<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $ITEM
 * @property string $SERIE
 * @property float $PRECIO
 * @property string $FECHA
 * @property string $OPERADOR
 * @property string $MAQUINA
 * @property string $HORA
 */
class ADMITEMLIQELE extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMITEMLIQELE';

    /**
     * @var array
     */
    protected $fillable = ['ITEM', 'SERIE', 'PRECIO', 'FECHA', 'OPERADOR', 'MAQUINA', 'HORA'];

}
