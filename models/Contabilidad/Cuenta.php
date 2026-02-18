<?php

namespace Model\Contabilidad;

use Model\ActiveRecord;

class Cuenta extends ActiveRecord
{
    protected static $tabla = 'cuentas';
    protected static $columnasDB = [
        'id',
        'codigo',
        'nombre',
        'tipo'
    ];

    public $id;
    public $codigo;
    public $nombre;
    public $tipo;

    public function __construct($args = [])
    {
        $this->id     = $args['id'] ?? null;
        $this->codigo = $args['codigo'] ?? '';
        $this->nombre = $args['nombre'] ?? '';
        $this->tipo   = $args['tipo'] ?? '';
    }

    public function validar()
    {
        self::$alertas = [];

        // =========================
        // CODIGO
        // =========================
        if (!$this->codigo) {
            self::$alertas['error'][] = 'El código de la cuenta es obligatorio';
        } else {

            // solo numeros
            if (!ctype_digit($this->codigo)) {
                self::$alertas['error'][] = 'El código de la cuenta solo puede contener números';
            }

            // unico
            $existe = self::where('codigo', $this->codigo);
            if ($existe && $existe->id != $this->id) {
                self::$alertas['error'][] = 'Este código de cuenta ya existe';
            }
        }

        // =========================
        // NOMBRE
        // =========================
        if (!$this->nombre) {
            self::$alertas['error'][] = 'El nombre de la cuenta es obligatorio';
        }

        // =========================
        // TIPO
        // =========================
        $tipos_permitidos = ['activo','pasivo','gasto','ingreso'];

        if (!$this->tipo) {
            self::$alertas['error'][] = 'Debe seleccionar el tipo de cuenta';
        } elseif (!in_array(strtolower($this->tipo), $tipos_permitidos)) {
            self::$alertas['error'][] = 'Tipo de cuenta inválido';
        }

        return self::$alertas;
    }
}
