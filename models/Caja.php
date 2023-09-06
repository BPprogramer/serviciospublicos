<?php 

    namespace Model;

    class Caja extends ActiveRecord{
        protected static $tabla = 'cajas';
        protected static $columnasDB = ['id','efectivo_inicial','total_recaudo','total_efectivo', 'total_transferencias', 'estado','fecha_apertura', 'fecha_cierre','usuario_id'];

        public $id;
        public $efectivo_inicial;
        public $total_recaudo; 
        public $total_efectivo;
        public $total_transferencias;
        public $estado;
        public $fecha_apertura;
        public $fecha_cierre;
        public $usuario_id;

        public function __construct($args = [])
        {
            $this->id = $args['id']??null;
            $this->efectivo_inicial = $args['efectivo_inicial']??'';
            $this->total_recaudo = $args['total_recaudo']??'';
            $this->total_efectivo = $args['total_efectivo']??'';
            $this->total_transferencias = $args['total_transferencias']??'';
            $this->usuario_id = $args['usuario_id']??'';
            $this->estado = $args['estado']??'';
            $this->fecha_apertura = $args['fecha_apertura']??'';
            $this->fecha_cierre = $args['fecha_cierre']??null;
            $this->usuario_id = $args['usuario_id']??'';
        }
        public function validar(){
            if(!$this->efectivo_inicial){
                self::$alertas['error'][] = 'El efectivo de Inicio en caja es obligatorio, si es cero por porfavor digite 0';
            }
           


            return self::$alertas;
        }

        public function formatearDatosFloat(){
            $this->efectivo_inicial = floatval(str_replace(',','',$this->efectivo_inicial));
            $this->total_recaudo = floatval(str_replace(',','',$this->total_recaudo));
            $this->total_efectivo = floatval(str_replace(',','',$this->total_efectivo));
            $this->total_transferencias = floatval(str_replace(',','',$this->total_transferencias));
       
        }
    }