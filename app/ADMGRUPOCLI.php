<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $CODIGO
 * @property string $NOMBRE
 * @property string $CLIENTE
 * @property string $RUTA
 * @property string $CLASE
 * @property string $VENDEDOR
 * @property string $TIPO
 * @property string $ESTADO
 */
class ADMGRUPOCLI extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMGRUPOCLI';

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
    protected $fillable = ['NOMBRE', 'CLIENTE', 'RUTA', 'CLASE', 'VENDEDOR', 'TIPO', 'ESTADO'];

}
