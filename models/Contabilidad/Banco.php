<?php

namespace Model\Contabilidad;

use Model\ActiveRecord;

class Banco extends ActiveRecord
{

    protected static $tabla = 'bancos';
    protected static $columnasDB = [
        'id',
        'nombre',
        'codigo',
        'nit',
        'dv'
    ];

    public $id;
    public $nombre;
    public $codigo;
    public $nit;
    public $dv;


    public function __construct($args = [])
    {
        $this->id     = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->codigo = $args['codigo'] ?? '';
        $this->nit    = $args['nit'] ?? '';
        $this->dv     = $args['dv'] ?? '';
    }

    public function validar()
    {

        self::$alertas = [];

        // Normalizar
        $this->nombre = strtoupper(trim($this->nombre));
        $this->codigo = trim($this->codigo);
        $this->nit    = trim($this->nit);
        $this->dv     = trim($this->dv);

        // ===============================
        // NOMBRE (OBLIGATORIO + ÚNICO)
        // ===============================

        if (!$this->nombre) {
            self::$alertas['error'][] = 'El nombre del banco es obligatorio';
        } else {

            $existe = self::where('nombre', $this->nombre);

            if ($existe && $existe->id != $this->id) {
                self::$alertas['error'][] = 'Este nombre ya está registrado';
            }
        }

        // ===============================
        // CÓDIGO (OPCIONAL + ÚNICO SI EXISTE)
        // ===============================

        if ($this->codigo !== '') {

            $existeCodigo = self::where('codigo', $this->codigo);

            if ($existeCodigo && $existeCodigo->id != $this->id) {
                self::$alertas['error'][] = 'Este código ya está registrado';
            }
        }

        // ===============================
        // NIT (OPCIONAL + NUMÉRICO + 9 DÍGITOS + ÚNICO SI EXISTE)
        // ===============================

        if ($this->nit !== '') {

            if (!ctype_digit($this->nit)) {
                self::$alertas['error'][] = 'El NIT debe ser numérico';
            } elseif (strlen($this->nit) != 9) {
                self::$alertas['error'][] = 'El NIT debe tener exactamente 9 dígitos';
            } else {

                $existeNit = self::where('nit', $this->nit);

                if ($existeNit && $existeNit->id != $this->id) {
                    self::$alertas['error'][] = 'Este NIT ya está registrado';
                }
            }
        }

        // ===============================
        // DV (OPCIONAL + 1 DÍGITO)
        // ===============================

        if ($this->dv !== '') {

            if (!ctype_digit($this->dv) || strlen($this->dv) != 1) {
                self::$alertas['error'][] = 'El DV debe ser un solo dígito numérico';
            }
        }

        return self::$alertas;
    }
}
