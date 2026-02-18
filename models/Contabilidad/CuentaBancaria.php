<?php

namespace Model\Contabilidad;

use Model\ActiveRecord;
use Model\Contabilidad\Banco;

class CuentaBancaria extends ActiveRecord
{

    protected static $tabla = 'cuentas_bancarias';
    protected static $columnasDB = [
        'id',
        'banco_id',
        'numero_cuenta',
        'nombre',
        'saldo_inicial'
    ];

    public $id;
    public $banco_id;
    public $numero_cuenta;
    public $nombre;
    public $saldo_inicial;


    public function __construct($args = [])
    {
        $this->id            = $args['id'] ?? null;
        $this->banco_id      = $args['banco_id'] ?? '';
        $this->numero_cuenta = $args['numero_cuenta'] ?? '';
        $this->nombre        = $args['nombre'] ?? '';
        $this->saldo_inicial = $args['saldo_inicial'] ?? '';
    }

    public function validar()
    {

        self::$alertas = [];

        // Normalizar
        $this->numero_cuenta = trim($this->numero_cuenta);
        $this->nombre        = trim($this->nombre);
        $this->saldo_inicial = trim($this->saldo_inicial);

        // ===============================
        // BANCO_ID (OBLIGATORIO Y DEBE EXISTIR)
        // ===============================

        if (!$this->banco_id) {

            self::$alertas['error'][] = 'Debe seleccionar un banco';

        } else {

            $banco = Banco::find($this->banco_id);

            if (!$banco) {
                self::$alertas['error'][] = 'El banco seleccionado no existe';
            }
        }

        // ===============================
        // NUMERO CUENTA (OBLIGATORIO + SOLO DIGITOS)
        // ===============================

        if (!$this->numero_cuenta) {

            self::$alertas['error'][] = 'El número de cuenta es obligatorio';

        } elseif (!ctype_digit($this->numero_cuenta)) {

            self::$alertas['error'][] = 'El número de cuenta solo debe contener dígitos';
        }

        // ===============================
        // SALDO INICIAL (OBLIGATORIO + NUMERICO)
        // ===============================

        if ($this->saldo_inicial === '') {

            self::$alertas['error'][] = 'El saldo inicial es obligatorio';

        } elseif (!is_numeric($this->saldo_inicial)) {

            self::$alertas['error'][] = 'El saldo inicial debe ser numérico';

        } else {

            // Forzar formato decimal correcto
            $this->saldo_inicial = number_format((float)$this->saldo_inicial, 2, '.', '');
        }

        return self::$alertas;
    }
}
