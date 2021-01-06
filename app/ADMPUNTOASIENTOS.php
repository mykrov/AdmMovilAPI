<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $indice
 * @property string $PUNTO
 * @property int $ANIO
 * @property string $TIPO
 * @property string $CUENTA
 * @property string $DEBE
 * @property string $CREDITO
 * @property string $ASIENTO
 * @property string $OPERADOR
 */
class ADMPUNTOASIENTOS extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMPUNTOASIENTOS';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'indice';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'float';

    /**
     * @var array
     */
    protected $fillable = ['PUNTO', 'ANIO', 'TIPO', 'CUENTA', 'DEBE', 'CREDITO', 'ASIENTO', 'OPERADOR'];

}
