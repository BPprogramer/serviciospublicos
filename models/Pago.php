<?php 

    namespace Model;

    class Pago extends ActiveRecord{
        protected static $tabla = 'pagos';
        protected static $columnasDB = ['id','numero_pago','fecha_pago', 'metodo', 'estado','factura_id', 'registrado_id','usuario_id'];

        public $id;
        public $numero_pago;
        public $fecha_pago; 
        public $metodo; 
        public $estado; 
        public $factura_id;
        public $registrado_id;
        public $usuario_id;

        public function __construct($args = [])
        {
            $this->id = $args['id']??null;
            $this->numero_pago = $args['numero_pago']??'';
            $this->fecha_pago = $args['fecha_pago']??'';
            $this->metodo = $args['metodo']??'';
            $this->estado = $args['estado']??'';
            $this->factura_id = $args['factura_id']??'';
            $this->registrado_id = $args['registrado_id']??'';
            $this->usuario_id = $args['usuario_id']??'';
        }
        


    }