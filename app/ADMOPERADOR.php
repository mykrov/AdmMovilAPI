<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * @property string $codigo
 * @property string $nombre
 * @property string $departamento
 * @property string $fechaing
 * @property string $clave
 * @property string $estado
 * @property string $PRUEBA
 * @property string $CATEGORIA
 * @property int $CODIGOCAJA
 * @property float $NUMULTAPERTURA
 * @property string $ESTADOCAJA
 * @property string $CLAVEMAESTRA
 * @property string $POSUSUARIOEDITAPRECIO
 * @property string $ESPTOVENTA
 * @property string $ESPTOVTAFAC
 * @property int $bodega
 * @property int $caja
 * @property string $relacionadobodega
 * @property string $COBRADOR
 */
class ADMOPERADOR extends Model
{
    use Notifiable,HasApiTokens;
    public $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMOPERADOR';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'codigo';

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
    protected $fillable = ['nombre', 'departamento', 'fechaing', 'clave', 'estado', 'PRUEBA', 'CATEGORIA', 'CODIGOCAJA', 'NUMULTAPERTURA', 'ESTADOCAJA', 'CLAVEMAESTRA', 'POSUSUARIOEDITAPRECIO', 'ESPTOVENTA', 'ESPTOVTAFAC', 'bodega', 'caja', 'relacionadobodega', 'COBRADOR'];

}
