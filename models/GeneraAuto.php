<?php 

    namespace Model;
    
    class GeneraAuto extends ActiveRecord{
        protected static $tabla = 'generaauto';
        protected static $columnasDB = ['id', 'auto'];

        public $id;
        public $auto;

        public function __construct($args = [])
        {
           $this->id =  $args['id']??null;
           $this->auto =  $args['id']??'';
        }
    }