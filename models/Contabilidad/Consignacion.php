<?php

namespace Model\Contabilidad;

use Model\ActiveRecord;
use Model\Contabilidad\CuentaBancaria;

class Consignacion extends ActiveRecord
{
    protected static $tabla = 'consignaciones';

    protected static $columnasDB = [
        'id',
        'fecha',
        'descripcion',
        'cuenta_bancaria_id',
        'usuario_id',
        'monto',
        'tipo',
        'ruta_comprobante',
        'anulado',
        'razon_anulacion'
    ];

    public $id;
    public $fecha;
    public $descripcion;
    public $cuenta_bancaria_id;
    public $usuario_id;
    public $monto;
    public $tipo;
    public $ruta_comprobante;
    public $anulado;
    public $razon_anulacion;

    public function __construct($args = [])
    {
        $this->id                 = $args['id'] ?? null;
        $this->fecha              = $args['fecha'] ?? '';
        $this->descripcion        = $args['descripcion'] ?? '';
        $this->cuenta_bancaria_id = $args['cuenta_bancaria_id'] ?? '';
        $this->usuario_id         = $args['usuario_id'] ?? '';
        $this->monto              = $args['monto'] ?? 0;
        $this->tipo               = $args['tipo'] ?? '';
        $this->ruta_comprobante   = $args['ruta_comprobante'] ?? null;
        $this->anulado            = $args['anulado'] ?? 0;
        $this->razon_anulacion    = $args['razon_anulacion'] ?? null;
    }

    public function validar()
    {
        self::$alertas = [];

        // =========================
        // CUENTA BANCARIA
        // =========================
        if (!$this->cuenta_bancaria_id) {
            self::$alertas['error'][] = 'Debe seleccionar una cuenta bancaria';
        } elseif (!filter_var($this->cuenta_bancaria_id, FILTER_VALIDATE_INT)) {
            self::$alertas['error'][] = 'Cuenta bancaria inválida';
        } else {
            $cuenta = CuentaBancaria::find($this->cuenta_bancaria_id);
            if (!$cuenta) {
                self::$alertas['error'][] = 'La cuenta bancaria no existe';
            }
        }

        // =========================
        // FECHA
        // =========================
        if (!$this->fecha) {
            self::$alertas['error'][] = 'La fecha es obligatoria';
        } else {
            $fechaValida = date_create($this->fecha);
            if (!$fechaValida) {
                self::$alertas['error'][] = 'Fecha inválida';
            } elseif ($this->fecha > date('Y-m-d')) {
                self::$alertas['error'][] = 'La fecha no puede ser mayor a la actual';
            }
        }

        // =========================
        // MONTO
        // =========================
        if ($this->monto === '' || $this->monto === null) {
            self::$alertas['error'][] = 'El monto es obligatorio';
        } elseif (!is_numeric($this->monto)) {
            self::$alertas['error'][] = 'El monto debe ser numérico';
        } elseif ($this->monto <= 0) {
            self::$alertas['error'][] = 'El monto debe ser mayor a 0';
        }

        // =========================
        // DESCRIPCIÓN
        // =========================
        if (!$this->descripcion) {
            self::$alertas['error'][] = 'La descripción es obligatoria';
        }

        // =========================
        // TIPO (ENUM)
        // =========================
        $tiposValidos = ['facturacion','subsidio','otros'];

        if (!$this->tipo) {
            self::$alertas['error'][] = 'El tipo es obligatorio';
        } elseif (!in_array($this->tipo, $tiposValidos)) {
            self::$alertas['error'][] = 'Tipo de consignación inválido';
        }

        return self::$alertas;
    }
}
