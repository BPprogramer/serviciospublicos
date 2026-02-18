<?php

namespace Model\Contabilidad;

use Model\ActiveRecord;
use Model\Contabilidad\CuentaBancaria;
use Model\Contabilidad\Tercero;

class Egreso extends ActiveRecord
{

    protected static $tabla = 'egresos';
    protected static $columnasDB = [
        'id',
        'fecha',
        'consecutivo',
        'tercero_id',
        'cuenta_bancaria_id',
        'detalle',
        'anulado',
        'razon_anulacion',
        'user_id'
    ];

    public $id;
    public $fecha;
    public $consecutivo;
    public $tercero_id;
    public $cuenta_bancaria_id;
    public $detalle;
    public $anulado;
    public $razon_anulacion;
    public $user_id;

    public function __construct($args = [])
    {
        $this->id                 = $args['id'] ?? null;
        $this->fecha              = $args['fecha'] ?? '';
        $this->consecutivo        = $args['consecutivo'] ?? '';
        $this->tercero_id         = $args['tercero_id'] ?? '';
        $this->cuenta_bancaria_id = $args['cuenta_bancaria_id'] ?? '';
        $this->detalle            = $args['detalle'] ?? '';
        $this->anulado         = $args['anulado'] ?? 0;
        $this->razon_anulacion    = $args['razon_anulacion'] ?? '';
        $this->user_id            = $args['user_id'] ?? '';
    }

    public function validar()
    {

        self::$alertas = [];

        // =========================
        // FECHA
        // =========================
        if (!$this->fecha) {
            self::$alertas['error'][] = 'La fecha es obligatoria';
        }

        // =========================
        // TERCERO
        // =========================
        if (!$this->tercero_id) {

            self::$alertas['error'][] = 'Debe seleccionar un tercero';
        } else {

            $tercero = Tercero::find($this->tercero_id);

            if (!$tercero) {
                self::$alertas['error'][] = 'El tercero seleccionado no existe';
            }
        }

        // =========================
        // CUENTA BANCARIA
        // =========================
        if (!$this->cuenta_bancaria_id) {

            self::$alertas['error'][] = 'Debe seleccionar una cuenta bancaria';
        } else {

            $cuenta = CuentaBancaria::find($this->cuenta_bancaria_id);

            if (!$cuenta) {
                self::$alertas['error'][] = 'La cuenta bancaria no existe';
            }
        }

        // =========================
        // DETALLE
        // =========================
        if (!$this->detalle) {
            self::$alertas['error'][] = 'El detalle del egreso es obligatorio';
        }

        // =========================
        // USER
        // =========================
        if (!$this->user_id) {
            self::$alertas['error'][] = 'Usuario no válido';
        }

        // =========================
        // ANULACION
        // =========================
        if ($this->anulado && !$this->razon_anulacion) {
            self::$alertas['error'][] = 'Debe indicar la razón de anulación';
        }

        return self::$alertas;
    }

    public static function ultimoConsecutivo()
    {
        $query = "SELECT consecutivo 
              FROM " . static::$tabla . " 
              ORDER BY consecutivo DESC 
              LIMIT 1";

        $resultado = self::$db->query($query);

        if (!$resultado || $resultado->num_rows === 0) {
            return 0;
        }

        $registro = $resultado->fetch_assoc();
        return (int) $registro['consecutivo'];
    }

    public function getConsecutivoFormateado()
    {
        $anio = date('Y', strtotime($this->fecha));
        return $anio . str_pad($this->consecutivo, 4, '0', STR_PAD_LEFT);
    }

    public static function totalCreditoCuentaBancaria($cuentaBancariaId)
    {
        $query = "
        SELECT SUM(ec.credito) as total
        FROM egresos_cuentas ec
        INNER JOIN egresos e ON ec.egreso_id = e.id
        WHERE e.cuenta_bancaria_id = '$cuentaBancariaId'
        AND e.anulado = 0
    ";

        $resultado = self::$db->query($query);
        $row = $resultado->fetch_assoc();

        return $row['total'] ?? 0;
    }
}
