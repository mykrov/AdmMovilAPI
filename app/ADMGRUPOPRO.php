<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $CODIGO
 * @property string $NOMBRE
 * @property string $ITEM
 * @property string $CATEGORIA
 * @property string $FAMILIA
 * @property string $LINEA
 * @property string $MARCA
 * @property string $ESTADO
 */
class ADMGRUPOPRO extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMGRUPOPRO';

    /**
     * @var array
     */
    protected $fillable = ['CODIGO', 'NOMBRE', 'ITEM', 'CATEGORIA', 'FAMILIA', 'LINEA', 'MARCA', 'ESTADO'];

}
