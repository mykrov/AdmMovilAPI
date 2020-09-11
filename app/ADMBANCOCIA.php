<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $nocuenta
 * @property string $CODIGO
 * @property string $cuenta
 * @property int $ANIOCUENTA
 * @property string $NOMBRE
 * @property int $ultpercon
 * @property float $ultchq
 * @property string $tipocuenta
 * @property string $autosn
 * @property string $cuentachq
 * @property int $ANIOCUENTACHQ
 * @property string $ESTADO
 * @property string $rutaarchivo
 * @property string $ultfechaconci
 */
class ADMBANCOCIA extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMBANCOCIA';

    /**
     * @var array
     */
    protected $fillable = ['cuenta', 'ANIOCUENTA', 'NOMBRE', 'ultpercon', 'ultchq', 'tipocuenta', 'autosn', 'cuentachq', 'ANIOCUENTACHQ', 'ESTADO', 'rutaarchivo', 'ultfechaconci'];

}
