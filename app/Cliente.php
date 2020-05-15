<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $CODIGO
 * @property string $RAZONSOCIAL
 * @property string $NEGOCIO
 * @property string $REPRESENTA
 * @property string $RUC
 * @property string $DIRECCION
 * @property string $TELEFONOS
 * @property string $FAX
 * @property string $EMAIL
 * @property string $TIPO
 * @property string $CATEGORIA
 * @property string $PROVINCIA
 * @property string $CANTON
 * @property string $PARROQUIA
 * @property string $SECTOR
 * @property string $RUTA
 * @property string $CTACLIENTE
 * @property float $CUPO
 * @property string $GRUPO
 * @property int $ORDEN
 * @property string $CODFRE
 * @property string $CREDITO
 * @property int $DIA
 * @property string $FECDESDE
 * @property string $FECHAING
 * @property string $FECULTCOM
 * @property string $FECELIMINA
 * @property string $ESTADO
 * @property string $TDCREDITO
 * @property int $DIASCREDIT
 * @property string $VENDEDOR
 * @property string $FORMAPAG
 * @property string $CREDITOA
 * @property string $IVA
 * @property string $BACKORDER
 * @property string $RETENPED
 * @property string $CONFINAL
 * @property string $CLASE
 * @property string $OBSERVACION
 * @property string $TIPONEGO
 * @property string $CODTMP
 * @property string $TIPODOC
 * @property string $TIPOCUENTA
 * @property string $CLIENTEWEB
 * @property string $CODCLIENTEDOMI
 * @property string $CLIENTEDOMI
 * @property string $REFERENCIA
 * @property string $TIPOCONTRIBUYENTE
 * @property string $RETIENEFUENTE
 * @property string $RETIENEIVA
 * @property string $ZONA
 * @property string $FECNAC
 * @property string $FECMOD
 * @property string $OPEMOD
 * @property float $PORDESSUGERIDO
 * @property string $EMAILSECUNDARIO
 * @property string $fechaenvioweb
 * @property int $FACTURAELECTRONICA
 * @property string $CLAVEFE
 * @property string $SUBIRWEB
 * @property string $TIPOPERSONA
 * @property string $SEXO
 * @property string $ESTADOCIVIL
 * @property string $ORIGENINGRESO
 * @property string $TIPOPAGO
 * @property string $FECHADESDE
 * @property string $FECHAHASTA
 * @property string $BENEFICIARIO
 * @property string $NOCONTRATO
 * @property string $TIPOPERSONAADICIONAL
 * @property string $GERENTE
 * @property string $TELEFONO
 * @property string $CONTACTOPAGO
 * @property string $CORREO1
 * @property string $CORREO2
 * @property string $CARGO1
 * @property string $CARGO2
 * @property string $OBSERVACIONADICIONAL
 * @property string $PAGOCUOTAS
 * @property int $NUMCUOTAS
 * @property int $CUOTA1
 * @property int $CUOTA2
 * @property int $CUOTA3
 * @property int $CUOTA4
 * @property int $CUOTA5
 * @property string $EJEX
 * @property string $EJEY
 * @property string $CLIENTEMOVIL
 * @property string $ESTADOPARAWEB
 * @property string $CLIRELACIONADO
 * @property string $VENDEDORAUX
 * @property string $CANAL
 * @property string $SUBCANAL
 * @property string $grupocliente
 * @property string $grupocredito
 * @property string $EWEB
 * @property string $nombre
 * @property string $FACTURABLE
 * @property string $DEUDAS
 * @property float $ANTICIPO
 * @property string $CIUDAD
 * @property string $coordenada
 * @property string $barrio
 * @property string $tipodocumento
 * @property string $CODIGOSAP
 * @property string $CodShip
 * @property string $longuitud
 * @property string $latitud
 * @property string $TipoLocalidad
 * @property string $numero
 * @property string $CORREOCARRO
 * @property string $CLAVECARRO
 */
class Cliente extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ADMCLIENTE';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'CODIGO';

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
    protected $fillable = ['RAZONSOCIAL', 'NEGOCIO', 'REPRESENTA', 'RUC', 'DIRECCION', 'TELEFONOS', 'FAX', 'EMAIL', 'TIPO', 'CATEGORIA', 'PROVINCIA', 'CANTON', 'PARROQUIA', 'SECTOR', 'RUTA', 'CTACLIENTE', 'CUPO', 'GRUPO', 'ORDEN', 'CODFRE', 'CREDITO', 'DIA', 'FECDESDE', 'FECHAING', 'FECULTCOM', 'FECELIMINA', 'ESTADO', 'TDCREDITO', 'DIASCREDIT', 'VENDEDOR', 'FORMAPAG', 'CREDITOA', 'IVA', 'BACKORDER', 'RETENPED', 'CONFINAL', 'CLASE', 'OBSERVACION', 'TIPONEGO', 'CODTMP', 'TIPODOC', 'TIPOCUENTA', 'CLIENTEWEB', 'CODCLIENTEDOMI', 'CLIENTEDOMI', 'REFERENCIA', 'TIPOCONTRIBUYENTE', 'RETIENEFUENTE', 'RETIENEIVA', 'ZONA', 'FECNAC', 'FECMOD', 'OPEMOD', 'PORDESSUGERIDO', 'EMAILSECUNDARIO', 'fechaenvioweb', 'FACTURAELECTRONICA', 'CLAVEFE', 'SUBIRWEB', 'TIPOPERSONA', 'SEXO', 'ESTADOCIVIL', 'ORIGENINGRESO', 'TIPOPAGO', 'FECHADESDE', 'FECHAHASTA', 'BENEFICIARIO', 'NOCONTRATO', 'TIPOPERSONAADICIONAL', 'GERENTE', 'TELEFONO', 'CONTACTOPAGO', 'CORREO1', 'CORREO2', 'CARGO1', 'CARGO2', 'OBSERVACIONADICIONAL', 'PAGOCUOTAS', 'NUMCUOTAS', 'CUOTA1', 'CUOTA2', 'CUOTA3', 'CUOTA4', 'CUOTA5', 'EJEX', 'EJEY', 'CLIENTEMOVIL', 'ESTADOPARAWEB', 'CLIRELACIONADO', 'VENDEDORAUX', 'CANAL', 'SUBCANAL', 'grupocliente', 'grupocredito', 'EWEB', 'nombre', 'FACTURABLE', 'DEUDAS', 'ANTICIPO', 'CIUDAD', 'coordenada', 'barrio', 'tipodocumento', 'CODIGOSAP', 'CodShip', 'longuitud', 'latitud', 'TipoLocalidad', 'numero', 'CORREOCARRO', 'CLAVECARRO'];

}
