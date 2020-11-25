<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $SECAUTO
 * @property string $TIPO
 * @property float $BODEGA
 * @property float $NUMERO
 * @property float $SECUENCIAL
 * @property string $CLIENTE
 * @property string $VENDEDOR
 * @property string $FECHA
 * @property string $HORA
 * @property string $ESTADO
 * @property float $SUBTOTAL
 * @property float $DESCUENTO
 * @property float $IVA
 * @property float $NETO
 * @property float $PESO
 * @property float $VOLUMEN
 * @property string $OPERADOR
 * @property string $COMENTARIO
 * @property string $OBSERVACION
 * @property float $FACTURA
 * @property float $GUIA
 * @property string $DOCFAC
 * @property string $DIASCRED
 * @property string $GRAVAIVA
 * @property string $HORAINI
 * @property string $HORAFIN
 * @property string $REGISTRADO
 * @property string $CREDITO
 * @property string $REGISTRAERROR
 * @property string $FECHACREA
 * @property string $HORACREA
 * @property string $CLTENUEVO
 * @property string $CODIGOMOVIL
 * @property string $SECAUTOPEDMOVIL
 */
class ADMCABPEDIDOX extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'SECAUTO';
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMCABPEDIDOX';

    /**
     * @var array
     */
    protected $fillable = ['SECAUTO', 'TIPO', 'BODEGA', 'NUMERO', 'SECUENCIAL', 'CLIENTE', 'VENDEDOR', 'FECHA', 'HORA', 'ESTADO', 'SUBTOTAL', 'DESCUENTO', 'IVA', 'NETO', 'PESO', 'VOLUMEN', 'OPERADOR', 'COMENTARIO', 'OBSERVACION', 'FACTURA', 'GUIA', 'DOCFAC', 'DIASCRED', 'GRAVAIVA', 'HORAINI', 'HORAFIN', 'REGISTRADO', 'CREDITO', 'REGISTRAERROR', 'FECHACREA', 'HORACREA', 'CLTENUEVO', 'CODIGOMOVIL', 'SECAUTOPEDMOVIL'];

}
