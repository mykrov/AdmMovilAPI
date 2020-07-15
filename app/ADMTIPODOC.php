<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $TIPO
 * @property string $NOMBRE
 * @property float $CONTADOR
 * @property string $SISTEMA
 * @property string $IE
 * @property string $TRANSACCION
 * @property int $TIPOCOM
 * @property string $IMPRIME
 */
class ADMTIPODOC extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMTIPODOC';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'TIPO';

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
    protected $fillable = ['NOMBRE', 'CONTADOR', 'SISTEMA', 'IE', 'TRANSACCION', 'TIPOCOM', 'IMPRIME'];

}
