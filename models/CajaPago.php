<?php 

    namespace Model;

    class CajaPago extends ActiveRecord{
        protected static $tabla = 'cajas_pagos';
        protected static $columnasDB = ['id','caja_id', 'pago_id'];

        public $id;
        public $caja_id;
        public $pago_id; 
  

        public function __construct($args = [])
        {
            $this->id = $args['id']??null;
            $this->caja_id = $args['caja_id']??'';
            $this->pago_id = $args['pago_id']??'';
    
        }
     
    }