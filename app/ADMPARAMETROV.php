<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $NOMBRECIA
 * @property string $MODULO
 * @property float $ANIO
 * @property string $TIPOCOSTO
 * @property int $FACLIN
 * @property float $SECUENCIAL
 * @property float $NUMGUIA
 * @property float $IVA
 * @property string $CATEGORIA
 * @property string $FAMILIA
 * @property string $LINEA
 * @property string $MARCA
 * @property string $MONEDA
 * @property string $INTEGCXC
 * @property int $BODFAC
 * @property float $NUMCONDI
 * @property string $INTVENCON
 * @property string $CLIENTE
 * @property string $TIPOMETA
 * @property string $SERIE
 * @property string $NOAUTO
 * @property string $FECHAVAL
 * @property string $IMPPOS
 * @property int $RANGOIMP
 * @property string $ACTPOS
 * @property string $CANGUI
 * @property string $FACSTK
 * @property string $IMPSRI
 * @property string $POSIMP
 * @property float $PORCOM
 * @property string $CARVEN
 * @property string $VARVEN
 * @property string $IMPCABGUI
 * @property float $NUMGRUPO
 * @property int $BODAUTO
 * @property float $NUMCARGA
 * @property string $CPAG
 * @property string $ACTCOND
 * @property float $NUMCOTI
 * @property string $GENDFA
 * @property string $CONFAC
 * @property string $BUSCA1
 * @property string $BUSCA2
 * @property string $MPORUTI
 * @property string $AUTOCODIGO
 * @property string $DESACT
 * @property string $NOAUTORET
 * @property string $SERIERET
 * @property string $FECHAVALRET
 * @property string $CAMPRECIO
 * @property string $NOAUTONVT
 * @property string $SERIENVT
 * @property string $FECHAVALNVT
 * @property string $NOAUTONCR
 * @property string $SERIENCR
 * @property string $FECHAVALNCR
 * @property string $IVANVT
 * @property string $intconcaja
 * @property string $intnofac
 * @property float $NUMGUIATRANS
 * @property int $NVTLIN
 * @property string $CONTROLCUPO
 * @property int $PRECIODEFAULT
 * @property int $BODEGAPOS
 * @property string $AUTOCODITEM
 * @property string $AUTOCALPRECIO
 * @property string $POSVENTACREDITO
 * @property int $POSINICIOCABECERA
 * @property int $POSINICIOPIE
 * @property int $POSINICIODETALLE
 * @property int $POSFACLIN
 * @property int $POSTIPODETALLE
 * @property int $POSINICIOINFOPOS
 * @property int $POSLINEAFINAL
 * @property int $POSVECESCOPIA
 * @property string $CONTROLVALVENCREDITO
 * @property string $CONTROLVALVENCONTADO
 * @property float $NUMEROAUTOVENTA
 * @property float $NLOCALIDAD
 * @property int $POSESQUEMAPRECIOS
 * @property string $FACTURAHASTA
 * @property float $OTRAEMPRWEB
 * @property string $RUTADOMI
 * @property string $POSIMPDATOSEMPRESA
 * @property string $POSDATOLINEA0
 * @property string $POSDATOPROPIETARIO
 * @property string $POSINICIOPIEDINAMICO
 * @property string $OCULTARCOLUMNACAJAS
 * @property string $DESCUENTOSENCADENA
 * @property string $RECARGAIVANVT
 * @property string $GRUPTOVENTA
 * @property int $ESQUEMAPAGOCOMI
 * @property int $LONGITUDAUTOCODITEM
 * @property string $CONNFVD
 * @property string $CENTROCOSTO
 * @property int $POSPRECIOVENTA
 * @property string $mensajefac
 * @property string $LISTAPRECIO
 * @property string $LINEAREDONDEA
 * @property string $FACTURAXBODEGA
 * @property int $ttesqcomi
 * @property string $cnfvd
 * @property string $coculcolumca
 * @property string $chabdescad
 * @property string $crecivanv
 * @property int $clongcodi
 * @property string $cautocalprecio
 * @property string $cautocoditem
 * @property int $NUMPROVINCIAS
 * @property string $serieguia
 * @property string $IMPRIMESERIE
 * @property string $proteina
 * @property string $EPRECIO
 * @property string $UNASOLABODEGA
 * @property string $FACTURAELECTRONICA
 * @property string $NOMBRESERVIDOR
 * @property string $NOMBREBASE
 * @property float $VALORMAXCF
 * @property int $BODEGAFE
 * @property string $usarcodbar
 * @property string $conexionvb
 * @property string $controlprod
 * @property string $numeroorden
 * @property string $FACENTPARCIAL
 * @property string $MOSADVCAR
 * @property string $secuencialuniconcr
 * @property string $conservarpedido
 * @property string $IMPHOJAC
 * @property string $tienedetfac
 * @property string $NUMFACDELPEDIDO
 * @property string $usardecimales
 * @property string $posrangoprecio
 * @property float $numerotoma
 * @property string $tienedessol
 * @property string $facturanegativo
 * @property string $ESQUEMAFARMA
 * @property string $impresoracargapos
 * @property string $imprimemenahorro
 * @property string $imprimecodigopos
 * @property string $UNILEVER
 * @property string $CODDISTRIBUIDOR
 * @property string $tipodocventa
 * @property string $itemcredihogar
 * @property float $porintcreditomensual
 * @property string $nedetel
 * @property string $facnedetel
 * @property string $ESNOTAVENTA
 * @property string $odbcempresa
 * @property string $controlcomisariato
 * @property string $CAMPRECIOPOS
 * @property string $campremantenimiento
 * @property string $FACSERSOS
 * @property float $numeroplantilla
 * @property string $DATOSCLIPOS
 * @property string $INGPREAJI
 * @property string $cambiavenpos
 * @property string $POSGENERAANTNCR
 * @property string $ESRANCHO
 * @property string $posmostrarmarca
 * @property string $POSCONSERVICIO
 * @property string $posconalumno
 * @property string $POSDOSLINEAS
 * @property string $poscontrolpreciocosto
 * @property string $posvendedor
 * @property string $cartera
 * @property string $USACARROCOMPRA
 * @property string $FOTOPOS
 * @property string $LISTADEPRECIO
 * @property string $LEYENDAPROFORMA
 * @property string $ESQUEMAELE
 */
class ADMPARAMETROV extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMPARAMETROV';

    /**
     * @var array
     */
    protected $fillable = ['NOMBRECIA', 'MODULO', 'ANIO', 'TIPOCOSTO', 'FACLIN', 'SECUENCIAL', 'NUMGUIA', 'IVA', 'CATEGORIA', 'FAMILIA', 'LINEA', 'MARCA', 'MONEDA', 'INTEGCXC', 'BODFAC', 'NUMCONDI', 'INTVENCON', 'CLIENTE', 'TIPOMETA', 'SERIE', 'NOAUTO', 'FECHAVAL', 'IMPPOS', 'RANGOIMP', 'ACTPOS', 'CANGUI', 'FACSTK', 'IMPSRI', 'POSIMP', 'PORCOM', 'CARVEN', 'VARVEN', 'IMPCABGUI', 'NUMGRUPO', 'BODAUTO', 'NUMCARGA', 'CPAG', 'ACTCOND', 'NUMCOTI', 'GENDFA', 'CONFAC', 'BUSCA1', 'BUSCA2', 'MPORUTI', 'AUTOCODIGO', 'DESACT', 'NOAUTORET', 'SERIERET', 'FECHAVALRET', 'CAMPRECIO', 'NOAUTONVT', 'SERIENVT', 'FECHAVALNVT', 'NOAUTONCR', 'SERIENCR', 'FECHAVALNCR', 'IVANVT', 'intconcaja', 'intnofac', 'NUMGUIATRANS', 'NVTLIN', 'CONTROLCUPO', 'PRECIODEFAULT', 'BODEGAPOS', 'AUTOCODITEM', 'AUTOCALPRECIO', 'POSVENTACREDITO', 'POSINICIOCABECERA', 'POSINICIOPIE', 'POSINICIODETALLE', 'POSFACLIN', 'POSTIPODETALLE', 'POSINICIOINFOPOS', 'POSLINEAFINAL', 'POSVECESCOPIA', 'CONTROLVALVENCREDITO', 'CONTROLVALVENCONTADO', 'NUMEROAUTOVENTA', 'NLOCALIDAD', 'POSESQUEMAPRECIOS', 'FACTURAHASTA', 'OTRAEMPRWEB', 'RUTADOMI', 'POSIMPDATOSEMPRESA', 'POSDATOLINEA0', 'POSDATOPROPIETARIO', 'POSINICIOPIEDINAMICO', 'OCULTARCOLUMNACAJAS', 'DESCUENTOSENCADENA', 'RECARGAIVANVT', 'GRUPTOVENTA', 'ESQUEMAPAGOCOMI', 'LONGITUDAUTOCODITEM', 'CONNFVD', 'CENTROCOSTO', 'POSPRECIOVENTA', 'mensajefac', 'LISTAPRECIO', 'LINEAREDONDEA', 'FACTURAXBODEGA', 'ttesqcomi', 'cnfvd', 'coculcolumca', 'chabdescad', 'crecivanv', 'clongcodi', 'cautocalprecio', 'cautocoditem', 'NUMPROVINCIAS', 'serieguia', 'IMPRIMESERIE', 'proteina', 'EPRECIO', 'UNASOLABODEGA', 'FACTURAELECTRONICA', 'NOMBRESERVIDOR', 'NOMBREBASE', 'VALORMAXCF', 'BODEGAFE', 'usarcodbar', 'conexionvb', 'controlprod', 'numeroorden', 'FACENTPARCIAL', 'MOSADVCAR', 'secuencialuniconcr', 'conservarpedido', 'IMPHOJAC', 'tienedetfac', 'NUMFACDELPEDIDO', 'usardecimales', 'posrangoprecio', 'numerotoma', 'tienedessol', 'facturanegativo', 'ESQUEMAFARMA', 'impresoracargapos', 'imprimemenahorro', 'imprimecodigopos', 'UNILEVER', 'CODDISTRIBUIDOR', 'tipodocventa', 'itemcredihogar', 'porintcreditomensual', 'nedetel', 'facnedetel', 'ESNOTAVENTA', 'odbcempresa', 'controlcomisariato', 'CAMPRECIOPOS', 'campremantenimiento', 'FACSERSOS', 'numeroplantilla', 'DATOSCLIPOS', 'INGPREAJI', 'cambiavenpos', 'POSGENERAANTNCR', 'ESRANCHO', 'posmostrarmarca', 'POSCONSERVICIO', 'posconalumno', 'POSDOSLINEAS', 'poscontrolpreciocosto', 'posvendedor', 'cartera', 'USACARROCOMPRA', 'FOTOPOS','LISTADEPRECIO','LEYENDAPROFORMA'];
}
