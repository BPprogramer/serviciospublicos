<?php

namespace Model\Contabilidad;

use Model\ActiveRecord;

class Tercero extends ActiveRecord
{

    protected static $tabla = 'terceros';
    protected static $columnasDB = [
        'id',
        'tipo_persona',
        'nombre',
        'documento',
        'dv',
        'telefono',
        'email',
        'direccion',
        'ciudad',

    ];

    public $id;
    public $tipo_persona;
    public $nombre;
    public $documento;
    public $dv;
    public $telefono;
    public $email;
    public $direccion;
    public $ciudad;


    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->tipo_persona = $args['tipo_persona'] ?? '';
        $this->nombre = $args['nombre'] ?? '';
        $this->documento = $args['documento'] ?? '';
        $this->dv = $args['dv'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->direccion = $args['direccion'] ?? '';
        $this->ciudad = $args['ciudad'] ?? '';
    }

    public function validar()
    {

        self::$alertas = []; // importante limpiar

        // validar tipo_persona
        $tipos_permitidos = ['natural', 'juridica'];

        if (!$this->tipo_persona) {
            self::$alertas['error'][] = 'Debe seleccionar el tipo de persona';
        } elseif (!in_array(strtolower($this->tipo_persona), $tipos_permitidos)) {
            self::$alertas['error'][] = 'El tipo de persona debe ser natural o juridica';
        }

        if (!$this->nombre) {
            self::$alertas['error'][] = 'El nombre es obligatorio';
        }

        if (!$this->documento) {
            self::$alertas['error'][] = 'El numero de documento es obligatorio';
        } else {

            // 🔥 VALIDAR UNICIDAD
            $existe = self::where('documento', $this->documento);

            if ($existe && $existe->id != $this->id) {
                self::$alertas['error'][] = 'Este documento ya está registrado';
            }
        }

        if (!$this->telefono) {
            self::$alertas['error'][] = 'El telefono es obligatorio';
        }

        if ($this->email && !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El email no es valido';
        }

        return self::$alertas;
    }
}
