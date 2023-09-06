<?php 
    namespace Model;
    use Model\ActiveRecord;

    class Estrato extends ActiveRecord{
        protected static $tabla = 'estratos';
        protected static $columnasDB = ['id','estrato','year', 'facturas_vencidas','tarifa_plena','porcentaje_subsidio','subsidio','porcentaje_copago','copago',
                                        'porcentaje_acu','tarifa_plena_acu','subsidio_acu','copago_acu','porcentaje_alc','tarifa_plena_alc',
                                        'subsidio_alc','copago_alc','porcentaje_aseo','tarifa_plena_aseo','subsidio_aseo','copago_aseo', 'porcentaje_ajuste','ajuste'];

        public $id;
        public $estrato;
        public $year;
        public $facturas_vencidas;
        public $tarifa_plena;
        public $porcentaje_subsidio;
        public $subsidio;
        public $porcentaje_copago;
        public $copago;
        public $porcentaje_acu;
        public $tarifa_plena_acu;
        public $subsidio_acu;
        public $copago_acu;
        public $porcentaje_alc;
        public $tarifa_plena_alc;
        public $subsidio_alc;
        public $copago_alc;
        public $porcentaje_aseo;
        public $tarifa_plena_aseo;
        public $subsidio_aseo;
        public $copago_aseo;
        public $porcentaje_ajuste;
        public $ajuste;

        public function __construct($args = [])
        {
            $this->id = $args['id']??null;
            $this->estrato = $args['estrato']??'';
            $this->year = $args['year']??'';
            $this->facturas_vencidas = $args['facturas_vencidas']??'';
            $this->tarifa_plena = $args['tarifa_plena']??'';
            $this->porcentaje_subsidio = $args['porcentaje_subsidio']??'';
            $this->subsidio = $args['subsidio']??'';
            $this->porcentaje_copago = $args['porcentaje_copago']??'';
            $this->copago = $args['copago']??'';
            $this->porcentaje_acu = $args['porcentaje_acu']??'';
            $this->tarifa_plena_acu = $args['tarifa_plena_acu']??'';
            $this->subsidio_acu = $args['subsidio_acu']??'';
            $this->copago_acu = $args['copago_acu']??'';
            $this->porcentaje_alc = $args['porcentaje_alc']??'';
            $this->tarifa_plena_alc = $args['tarifa_plena_alc']??'';
            $this->subsidio_alc = $args['subsidio_alc']??'';
            $this->copago_alc = $args['copago_alc']??'';
            $this->porcentaje_aseo = $args['porcentaje_aseo']??'';
            $this->tarifa_plena_aseo = $args['tarifa_plena_aseo']??'';
            $this->subsidio_aseo = $args['subsidio_aseo']??'';
            $this->copago_aseo = $args['copago_aseo']??'';
            $this->porcentaje_ajuste = $args['porcentaje_ajuste']??'';
            $this->ajuste = $args['ajuste']??'';
        }

      
        public function validar(){
            if(!$this->estrato){
                self::$alertas['error'][] = 'El Nombre del Estrato es Obligatorio';
            }
            if(!$this->year){
                self::$alertas['error'][] = 'El Año Vigente es Obligatorio';
            }
            if($this->facturas_vencidas==''){
                self::$alertas['error'][] = 'El Número de Facturas vencidas es Obligatorio';
            }
            
            if($this->tarifa_plena==''){
                self::$alertas['error'][] = 'La Tarifa Plena es Obligatoria';
            }
            if($this->porcentaje_subsidio==''){
                self::$alertas['error'][] = 'El porcentaje del Subsidio es Obligatorio';
            }
            if($this->porcentaje_acu==''){
                self::$alertas['error'][] = 'Los Datos del Acueducto son obliatorios';
            }
            
            if($this->porcentaje_alc==''){
                self::$alertas['error'][] = 'Los Datos del Alcantarillado son obliatorios';
            }
            if($this->porcentaje_aseo==''){
                self::$alertas['error'][] = 'Los Datos del Aseo son obliatorios';
            }
            if($this->porcentaje_acu!='' && $this->porcentaje_alc!='' && $this->porcentaje_aseo!=''){
                $suma_100 = $this->porcentaje_acu+$this->porcentaje_alc+$this->porcentaje_aseo;
                if($suma_100!=100){
                    self::$alertas['error'][] = 'La suma de los Porcentajes del Acueducto, Alcantarillado y Aseo debe ser Igual a 100%';
                }
            }
      
        

            return self::$alertas;
        }
        public function formatearDatosFloat(){
            $this->tarifa_plena = floatval(str_replace(',','',$this->tarifa_plena));
            $this->subsidio = floatval(str_replace(',','',$this->subsidio));
            $this->copago = floatval(str_replace(',','',$this->copago));
            $this->tarifa_plena_acu = floatval(str_replace(',','',$this->tarifa_plena_acu));
            $this->subsidio_acu = floatval(str_replace(',','',$this->subsidio_acu));
            $this->copago_acu = floatval(str_replace(',','',$this->copago_acu));
            $this->tarifa_plena_alc = floatval(str_replace(',','',$this->tarifa_plena_alc));
            $this->subsidio_alc = floatval(str_replace(',','',$this->subsidio_alc));
            $this->copago_alc = floatval(str_replace(',','',$this->copago_alc));
            $this->tarifa_plena_aseo = floatval(str_replace(',','',$this->tarifa_plena_aseo));
            $this->subsidio_aseo = floatval(str_replace(',','',$this->subsidio_aseo));
            $this->copago_aseo = floatval(str_replace(',','',$this->copago_aseo));
     
            $this->porcentaje_copago = 100 - $this->porcentaje_subsidio;
            $this->ajuste = floatval(str_replace(',','',$this->ajuste));
        }
        public function validarAjuste(){
            if($this->porcentaje_ajuste == '' || $this->porcentaje_ajuste == 0){
                $this->ajuste = 0;
                $this->porcentaje_ajuste = 0;
            }

        }
        public function formatearDatosNumber(){
            $this->tarifa_plena = number_format($this->tarifa_plena);
            $this->subsidio = number_format($this->subsidio);
            $this->copago = number_format($this->copago);
            $this->tarifa_plena_acu = number_format($this->tarifa_plena_acu);
            $this->subsidio_acu = number_format($this->subsidio_acu);
            $this->copago_acu = number_format($this->copago_acu);
            $this->tarifa_plena_alc = number_format($this->tarifa_plena_alc);
            $this->subsidio_alc = number_format($this->subsidio_alc);
            $this->copago_alc = number_format($this->copago_alc);
            $this->tarifa_plena_aseo = number_format($this->tarifa_plena_aseo);
            $this->subsidio_aseo = number_format($this->subsidio_aseo);
            $this->copago_aseo = number_format($this->copago_aseo);
            $this->ajuste = number_format($this->ajuste);
         
        }

        

        

    }