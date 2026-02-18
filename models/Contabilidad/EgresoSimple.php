<?php

namespace Model\Contabilidad;

use Model\ActiveRecord;
use Model\Contabilidad\CuentaBancaria;
use Model\Usuario;

class EgresoSimple extends ActiveRecord
{
    protected static $tabla = 'egresos_simples';

    protected static $columnasDB = [
        'id',
        'cuenta_bancaria_id',
        'user_id',
        'fecha',
        'monto',
        'descripcion',
        'anulado',
        'razon_anulacion'
    ];

    public $id;
    public $cuenta_bancaria_id;
    public $user_id;
    public $fecha;
    public $monto;
    public $descripcion;
    public $anulado;
    public $razon_anulacion;

    public function __construct($args = [])
    {
        $this->id                 = $args['id'] ?? null;
        $this->cuenta_bancaria_id = $args['cuenta_bancaria_id'] ?? '';
        $this->user_id            = $args['user_id'] ?? '';
        $this->fecha              = $args['fecha'] ?? '';
        $this->monto              = $args['monto'] ?? 0;
        $this->descripcion        = $args['descripcion'] ?? '';
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
        } else {

            if (!filter_var($this->cuenta_bancaria_id, FILTER_VALIDATE_INT)) {
                self::$alertas['error'][] = 'Cuenta bancaria inválida';
            } else {
                $cuenta = CuentaBancaria::find($this->cuenta_bancaria_id);
                if (!$cuenta) {
                    self::$alertas['error'][] = 'La cuenta bancaria no existe';
                }
            }
        }

        // =========================
        // USUARIO RESPONSABLE
        // =========================
        if (!$this->user_id) {
            self::$alertas['error'][] = 'Usuario responsable inválido';
        } else {

            if (!filter_var($this->user_id, FILTER_VALIDATE_INT)) {
                self::$alertas['error'][] = 'Usuario inválido';
            } else {
                $usuario = Usuario::find($this->user_id);
                if (!$usuario) {
                    self::$alertas['error'][] = 'El usuario no existe';
                }
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
        } elseif (strlen($this->descripcion) > 1000) {
            self::$alertas['error'][] = 'La descripción no puede superar los 1000 caracteres';
        }

        return self::$alertas;
    }
}
