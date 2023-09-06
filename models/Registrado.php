<?php 

    namespace Model;
    use Model\ActiveRecord;

    class Registrado extends ActiveRecord{
        protected static $tabla = 'registrados';
        protected static $columnasDB = ['id', 'nombre','apellido','cedula_nit','celular','barrio','direccion','codigo_ubicacion','estrato_id','estado', 'facturas'];

        public $id;
        public $nombre;
        public $apellido;
        public $cedula_nit;
        public $celular;
        public $barrio;
        public $direccion;
        public $codigo_ubicacion;
        public $estrato_id;
        public $estado;
        public $facturas;

        public function __construct($args = [])
        {
            $this->id = $args['id']??null;
            $this->nombre = $args['nombre']??'';
            $this->apellido = $args['apellido']??'';
            $this->cedula_nit = $args['cedula_nit']??'';
            $this->celular = $args['celular']??'';
            $this->barrio = $args['barrio']??'';
            $this->direccion = $args['direccion']??'';
            $this->codigo_ubicacion = $args['codigo_ubicacion']??'';
            $this->estrato_id = $args['estrato_id']??'';
            $this->estado = $args['estado']??1;
            $this->facturas = $args['estado']??'';
        }

        public function validar(){
            if(!$this->nombre){
                self::$alertas['error'][] = 'El Nombre es Obligatorio';
            }
            if(!$this->apellido){
                self::$alertas['error'][] = 'El Apellido es Obligatorio';
            }
            if(!$this->cedula_nit){
                self::$alertas['error'][] = 'La Cedula o el Nit es Obligatorio';
            }
            if(!$this->celular){
                self::$alertas['error'][] = 'El Número de Célular es Obligatorio';
            }else if(strlen($this->celular)!=10){
                self::$alertas['error'][] = 'El Número de Célular no es Válido';
            }
            if(!$this->barrio){
                self::$alertas['error'][] = 'El Barrio de Residencia es Obligatorio';
            }
            if(!$this->direccion){
                self::$alertas['error'][] = 'La Dirección de Residencia es Obligatorio';
            }
            if(!$this->codigo_ubicacion){
                self::$alertas['error'][] = 'El Código que Ubica la Residencia es Obligatorio';
            }
            if(!$this->estrato_id){
                self::$alertas['error'][] = 'El Estrato es Obligatorio';
            }
            return self::$alertas;
        }

    }