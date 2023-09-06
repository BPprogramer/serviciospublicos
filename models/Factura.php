<?php 
    namespace Model;
    use Model\ActiveRecord;

    class Factura extends ActiveRecord{
        protected static $tabla = 'facturas';
        protected static $columnasDB = ['id','numero_factura','registrado_id','mes_facturado','fecha_emision','estrato','tarifa_plena','subsidio','copago',
                                        'subsidio_acu','copago_acu','subsidio_alc','copago_alc','subsidio_aseo','copago_aseo', 'ajuste', 'pagado'];

        public $id;
        public $numero_factura;
        public $registrado_id;
        public $mes_facturado;
 
        public $fecha_emision;
        public $estrato;
        public $tarifa_plena;
        public $subsidio;
        public $copago;
        public $subsidio_acu;
        public $copago_acu;
        public $subsidio_alc;
        public $copago_alc;
        public $subsidio_aseo;
        public $copago_aseo;
        public $ajuste;
        public $pagado;

        public function __construct($args = [])
        {
            $this->id = $args['id']??null;
            $this->numero_factura = $args['numero_factura']??'';
            $this->registrado_id = $args['registrado_id']??'';
            $this->mes_facturado = $args['mes_facturado']??'';
         
            $this->fecha_emision = $args['fecha_emision']??'';
            $this->estrato = $args['estrato']??'';
            $this->tarifa_plena = $args['tarifa_plena']??'';
            $this->subsidio = $args['subsidio']??'';
            $this->copago = $args['copago']??'';
            $this->subsidio_acu = $args['subsidio_acu']??'';
            $this->copago_acu = $args['copago_acu']??'';
            $this->subsidio_alc = $args['subsidio_alc']??'';
            $this->copago_alc = $args['copago_alc']??'';
            $this->subsidio_aseo = $args['subsidio_aseo']??'';
            $this->copago_aseo = $args['copago_aseo']??'';
            $this->ajuste = $args['ajuste']??'';
            $this->pagado = $args['pagado']??'';
        }

        public function validarAjuste(){
            if($this->ajuste == ''){
                $this->ajuste = 0;
           
            }

        }
        public function formatearDatosNumber(){
            $this->tarifa_plena = number_format($this->tarifa_plena);
            $this->subsidio = number_format($this->subsidio);
            $this->copago = number_format($this->copago);
            $this->subsidio_acu = number_format($this->subsidio_acu);
            $this->copago_acu = number_format($this->copago_acu);
            $this->subsidio_alc = number_format($this->subsidio_alc);
            $this->copago_alc = number_format($this->copago_alc);
            $this->subsidio_aseo = number_format($this->subsidio_aseo);
            $this->copago_aseo = number_format($this->copago_aseo);
            $this->ajuste = number_format($this->ajuste);
         
        }

        

        

    }