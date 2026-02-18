<?php

namespace Model\Contabilidad;

use Model\ActiveRecord;
use Model\Contabilidad\Cuenta;

class EgresoCuenta extends ActiveRecord
{

    protected static $tabla = 'egresos_cuentas';
    protected static $columnasDB = [
        'id',
        'egreso_id',
        'cuenta_id',
        'debito',
        'credito'
    ];

    public $id;
    public $egreso_id;
    public $cuenta_id;
    public $debito;
    public $credito;

    public function __construct($args = [])
    {
        $this->id        = $args['id'] ?? null;
        $this->egreso_id = $args['egreso_id'] ?? '';
        $this->cuenta_id = $args['cuenta_id'] ?? '';
        $this->debito    = $args['debito'] ?? 0;
        $this->credito   = $args['credito'] ?? 0;
    }

    public function validar()
    {

        self::$alertas = [];

        // =========================
        // CUENTA
        // =========================
        if (!$this->cuenta_id) {

            self::$alertas['error'][] = 'Debe seleccionar una cuenta contable';

        } else {

            $cuenta = Cuenta::find($this->cuenta_id);

            if (!$cuenta) {
                self::$alertas['error'][] = 'La cuenta contable no existe';
            }
        }

        // =========================
        // DEBITO Y CREDITO
        // =========================

        if (!is_numeric($this->debito) || $this->debito < 0) {
            self::$alertas['error'][] = 'El débito debe ser numérico';
        }

        if (!is_numeric($this->credito) || $this->credito < 0) {
            self::$alertas['error'][] = 'El crédito debe ser numérico';
        }

        if ($this->debito == 0 && $this->credito == 0) {
            self::$alertas['error'][] = 'Debe registrar un débito o un crédito';
        }

        return self::$alertas;
    }
}
