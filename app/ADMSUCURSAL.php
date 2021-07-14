<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $CODIGO
 * @property string $NOMBRE
 * @property string $DIRECCION
 * @property string $CIUDAD
 * @property string $PAIS
 * @property string $ESTADO
 * @property string $CLIENTE
 */
class ADMSUCURSAL extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMSUCURSAL';

    /**
     * @var array
     */
    protected $fillable = ['CODIGO', 'NOMBRE', 'DIRECCION', 'CIUDAD', 'PAIS', 'ESTADO', 'CLIENTE'];

}
