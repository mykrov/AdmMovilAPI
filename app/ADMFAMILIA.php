<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $CODIGO
 * @property string $CATEGORIA
 * @property string $NOMBRE
 * @property string $ESTADO
 * @property string $EWEB
 */
class ADMFAMILIA extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMFAMILIA';

    /**
     * @var array
     */
    protected $fillable = ['NOMBRE', 'ESTADO', 'EWEB'];

}
