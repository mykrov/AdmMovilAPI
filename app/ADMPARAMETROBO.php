<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $modulo
 * @property string $nombrecia
 * @property string $contador
 * @property string $registro
 * @property string $gerente
 * @property string $direccion
 * @property string $ciudad
 * @property string $telefono
 * @property string $ruc
 * @property int $anio
 * @property int $nivel1
 * @property int $nivel2
 * @property int $nivel3
 * @property int $nivel4
 * @property int $nivel5
 * @property int $nivel6
 * @property float $mescon
 * @property float $lognivaux
 * @property float $secuencial
 * @property string $ctapergan
 * @property string $ctaperganant
 * @property string $ctaanticipo
 * @property string $ctamulta
 * @property float $securecu
 * @property string $ctaivacom
 * @property string $ctaivavta
 * @property float $tipcomban
 * @property string $comper
 * @property string $fax
 * @property string $mail
 * @property string $provincia
 * @property string $ctaconsi
 * @property float $saldo_mov
 * @property string $idcnt
 * @property string $idlegal
 * @property string $tipoid
 * @property string $seriefac
 * @property string $noauto
 * @property string $fechaval
 * @property string $INTCLICUE
 * @property string $CODIGOPROV
 * @property string $CONCILIARCHQCOBRADO
 * @property string $conciliaanioant
 * @property string $conciliactabco
 * @property string $controlcierremen
 * @property string $sucursal
 * @property string $pagina_web
 * @property string $url_ws
 * @property string $integrarfacncr
 * @property int $NUMEROETIQUETA
 * @property string $FECHACONTABLE
 * @property string $MESCONTABLE
 * @property int $MESCONTROL
 * @property int $mescontablesis
 * @property string $fechapedido
 * @property string $rucprov
 * @property string $RETENPORSERIE
 * @property integer $NUMLIQUIDACION
 */
class ADMPARAMETROBO extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMPARAMETROBO';

    /**
     * @var array
     */
    protected $fillable = ['modulo', 'nombrecia', 'contador', 'registro', 'gerente', 'direccion', 'ciudad', 'telefono', 'ruc', 'anio', 'nivel1', 'nivel2', 'nivel3', 'nivel4', 'nivel5', 'nivel6', 'mescon', 'lognivaux', 'secuencial', 'ctapergan', 'ctaperganant', 'ctaanticipo', 'ctamulta', 'securecu', 'ctaivacom', 'ctaivavta', 'tipcomban', 'comper', 'fax', 'mail', 'provincia', 'ctaconsi', 'saldo_mov', 'idcnt', 'idlegal', 'tipoid', 'seriefac', 'noauto', 'fechaval', 'INTCLICUE', 'CODIGOPROV', 'CONCILIARCHQCOBRADO', 'conciliaanioant', 'conciliactabco', 'controlcierremen', 'sucursal', 'pagina_web', 'url_ws', 'integrarfacncr', 'NUMEROETIQUETA', 'FECHACONTABLE', 'MESCONTABLE', 'MESCONTROL', 'mescontablesis', 'fechapedido', 'rucprov', 'RETENPORSERIE', 'NUMLIQUIDACION'];

}
