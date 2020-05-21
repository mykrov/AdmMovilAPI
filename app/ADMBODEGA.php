<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $CODIGO
 * @property string $NOMBRE
 * @property string $DIRECCION
 * @property string $CIUDAD
 * @property string $BODEGUERO
 * @property float $NOING
 * @property float $NOEGR
 * @property float $NOFACTURA
 * @property float $NOPEDIDO
 * @property float $NOCOM
 * @property float $NODCOM
 * @property float $NOAJI
 * @property float $NOAJE
 * @property float $NOTRAIN
 * @property float $NOTRAEG
 * @property string $ESTADO
 * @property float $NOOCO
 * @property float $NONOTA
 * @property float $NCR
 * @property float $NCRINT
 * @property float $NORETENCION
 * @property float $NCRPROV
 * @property float $NOCARGAEGRESO
 * @property float $NOCARGAINGRESO
 * @property float $NUMGUIAREMISION
 * @property int $NOPROCESO
 * @property string $SERIE
 * @property string $NOAUTORIZACIONFAC
 * @property string $FECHAVDESDEFAC
 * @property string $FECHAVHASTAFAC
 * @property string $NOAUTOSRIFAC
 * @property string $NOAUTORIZACIONNCR
 * @property string $FECHAVDESDENCR
 * @property string $FECHAVHASTANCR
 * @property string $NOAUTOSRINCR
 * @property string $NOAUTORIZACIONNVT
 * @property string $FECHAVDESDENVT
 * @property string $FECHAVHASTANVT
 * @property string $NOAUTOSRINVT
 * @property string $NOAUTORIZACIONRET
 * @property string $FECHAVDESDERET
 * @property string $FECHAVHASTARET
 * @property string $NOAUTOSRIRET
 * @property string $BODATS
 * @property int $NOCONTROLPACIENTE
 * @property float $numguiaremisionpos
 * @property string $TELEFONO
 * @property string $CODIGO_BODEGA
 * @property float $NOIMPORTACION
 */
class ADMBODEGA extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMBODEGA';

    /**
     * @var array
     */
    protected $fillable = ['CODIGO', 'NOMBRE', 'DIRECCION', 'CIUDAD', 'BODEGUERO', 'NOING', 'NOEGR', 'NOFACTURA', 'NOPEDIDO', 'NOCOM', 'NODCOM', 'NOAJI', 'NOAJE', 'NOTRAIN', 'NOTRAEG', 'ESTADO', 'NOOCO', 'NONOTA', 'NCR', 'NCRINT', 'NORETENCION', 'NCRPROV', 'NOCARGAEGRESO', 'NOCARGAINGRESO', 'NUMGUIAREMISION', 'NOPROCESO', 'SERIE', 'NOAUTORIZACIONFAC', 'FECHAVDESDEFAC', 'FECHAVHASTAFAC', 'NOAUTOSRIFAC', 'NOAUTORIZACIONNCR', 'FECHAVDESDENCR', 'FECHAVHASTANCR', 'NOAUTOSRINCR', 'NOAUTORIZACIONNVT', 'FECHAVDESDENVT', 'FECHAVHASTANVT', 'NOAUTOSRINVT', 'NOAUTORIZACIONRET', 'FECHAVDESDERET', 'FECHAVHASTARET', 'NOAUTOSRIRET', 'BODATS', 'NOCONTROLPACIENTE', 'numguiaremisionpos', 'TELEFONO', 'CODIGO_BODEGA', 'NOIMPORTACION'];

}
