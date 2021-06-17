<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $CODIGO
 * @property string $CATEGORIA
 * @property string $FAMILIA
 * @property string $LINEA
 * @property string $NOMBRE
 * @property string $ESTADO
 * @property string $EWEB
 * @property string $CODIGOSAP
 */
class ADMMARCA extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMMARCA';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'CODIGO';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['CATEGORIA', 'FAMILIA', 'LINEA', 'NOMBRE', 'ESTADO', 'EWEB', 'CODIGOSAP'];

}
