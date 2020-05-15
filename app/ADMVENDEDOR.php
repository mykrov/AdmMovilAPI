<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * @property string $CODIGO
 * @property string $SUPERVISOR
 * @property string $TIPO
 * @property string $NOMBRE
 * @property string $DIRECCION
 * @property string $CEDULA
 * @property string $TELEFONOS
 * @property string $FECHAING
 * @property string $OBSERVACION
 * @property string $ESTADO
 * @property string $VENCOB
 * @property float $SECPPC
 * @property string $CLAVE
 * @property string $PPC
 * @property float $PORCOMICOB
 * @property float $PORCOMIVEN
 * @property float $SECPAGO
 * @property float $CUPOCARTERA
 * @property string $CLAVEWEB
 * @property string $VENDDOMI
 * @property string $controlavalorfac
 * @property float $valorfacminimo
 * @property string $creacliexterno
 * @property string $creadespedexterno
 * @property string $editaprecio
 * @property string $esautoventa
 * @property int $bodega
 * @property string $REGION
 * @property string $email
 * @property string $ventop
 * @property string $admventa
 * @property float $noventa
 * @property int $caja
 * @property int $numapertura
 * @property string $operadormovil
 * @property string $CODIGOSAP
 * @property string $CODIGOCARGOSAP
 * @property string $CodShip
 * @property string $apellidos
 * @property string $HASH
 */
class ADMVENDEDOR extends Authenticatable
{
    use Notifiable,HasApiTokens;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMVENDEDOR';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'CEDULA';

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
    protected $fillable = ['SUPERVISOR', 'TIPO', 'NOMBRE', 'DIRECCION', 'CEDULA', 'TELEFONOS', 'FECHAING', 'OBSERVACION', 'ESTADO', 'VENCOB', 'SECPPC', 'CLAVE', 'PPC', 'PORCOMICOB', 'PORCOMIVEN', 'SECPAGO', 'CUPOCARTERA', 'CLAVEWEB', 'VENDDOMI', 'controlavalorfac', 'valorfacminimo', 'creacliexterno', 'creadespedexterno', 'editaprecio', 'esautoventa', 'bodega', 'REGION', 'email', 'ventop', 'admventa', 'noventa', 'caja', 'numapertura', 'operadormovil', 'CODIGOSAP', 'CODIGOCARGOSAP', 'CodShip', 'apellidos', 'HASH'];

}
