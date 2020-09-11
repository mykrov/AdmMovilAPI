<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $TIPO
 * @property string $NOMBRE
 * @property float $NUMERO
 * @property string $IMPRIME
 * @property string $ESTADO
 * @property float $TIPOCOM
 * @property string $TRANSACCIO
 */
class ADMTIPODOCPROV extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMTIPODOCPROV';

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
    protected $fillable = ['NOMBRE', 'NUMERO', 'IMPRIME', 'ESTADO', 'TIPOCOM', 'TRANSACCIO'];

}
