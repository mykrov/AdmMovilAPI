<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $SECUENCIAL
 * @property int $BODEGA
 * @property string $CLIENTE
 * @property string $TIPO
 * @property float $NUMERO
 * @property string $SERIE
 * @property float $SECINV
 * @property string $TIPOCR
 * @property float $NUMCRE
 * @property string $TIPOREL
 * @property float $NUMEROREL
 * @property string $SERIECRE
 * @property string $NOAUTOR
 * @property string $MOTIVO
 * @property string $FECHA
 * @property float $IVA
 * @property float $MONTO
 * @property float $SALDO
 * @property string $OPERADOR
 * @property string $OBSERVACION
 * @property string $INTEGRADO
 * @property float $SECCON
 * @property float $NOGUIA
 * @property float $NOCARGA
 * @property string $VENDEDOR
 * @property string $APLSRI
 * @property string $HORA
 * @property string $NOMBREPC
 * @property float $COSTO
 * @property string $INCDVTAS
 * @property float $SUBTOTAL
 * @property float $SUBTOTAL0
 * @property string $ORINCR
 * @property int $CAJAC
 * @property float $INDICE
 * @property string $ESTADO
 * @property string $estafirmado
 * @property string $ACT_SCT
 * @property float $seccreditogen
 */
class ADMCREDITO extends Model
{
    public  $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMCREDITO';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'INDICE';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'float';

    /**
     * @var array
     */
    protected $fillable = ['SECUENCIAL', 'BODEGA', 'CLIENTE', 'TIPO', 'NUMERO', 'SERIE', 'SECINV', 'TIPOCR', 'NUMCRE', 'TIPOREL', 'NUMEROREL', 'SERIECRE', 'NOAUTOR', 'MOTIVO', 'FECHA', 'IVA', 'MONTO', 'SALDO', 'OPERADOR', 'OBSERVACION', 'INTEGRADO', 'SECCON', 'NOGUIA', 'NOCARGA', 'VENDEDOR', 'APLSRI', 'HORA', 'NOMBREPC', 'COSTO', 'INCDVTAS', 'SUBTOTAL', 'SUBTOTAL0', 'ORINCR', 'CAJAC', 'ESTADO', 'estafirmado', 'ACT_SCT', 'seccreditogen'];

}
