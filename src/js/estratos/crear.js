
(function(){
    const estrato = document.querySelector('#estrato');
    if(estrato){

        /* Selectores */
        const tarifaPlenaInput= document.querySelector('#tarifa_plena');
        const porcentajeSubsidioInput = document.querySelector('#porcentaje_subsidio');
        const subsidioInput= document.querySelector('#subsidio') ;
        const copagoInput = document.querySelector('#copago')

        const porcentajeAcuInput= document.querySelector('#porcentaje_acu');
        const subsidioAcuInput= document.querySelector('#subsidio_acu') ;
        const copagoAcuInput = document.querySelector('#copago_acu');
        const totalAcuInput = document.querySelector('#tarifa_plena_acu')

        const porcentajeAlcInput = document.querySelector('#porcentaje_alc');
        const subsidioAlcInput= document.querySelector('#subsidio_alc') ;
        const copagoAlcInput = document.querySelector('#copago_alc');
        const totalAlcInput = document.querySelector('#tarifa_plena_alc')
        

        const porcentajeAseoInput = document.querySelector('#porcentaje_aseo');
        const subsidioAseoInput= document.querySelector('#subsidio_aseo') ;
        const copagoAseoInput = document.querySelector('#copago_aseo');
        const totalAseoInput = document.querySelector('#tarifa_plena_aseo')

        const ajusteInput = document.querySelector('#ajuste');
        const porcentajeAjusteInput = document.querySelector('#porcentaje_ajuste');

        /* Eventos */

        tarifaPlenaInput.addEventListener('input',function(e){
            formaterValor(e);
            calcularSubsidioCopago();
            revisarValores();
            ajusteInput.value = '';
            porcentajeAjusteInput.value = '';
        
        })

        porcentajeSubsidioInput.addEventListener('input',function(e){
            const porcentaje = (e.target.value)*1
            if(porcentaje<0 || porcentaje>100){
             
                e.target.value = '';
            }
            calcularSubsidioCopago();
            revisarValores();
            ajusteInput.value = '';
            porcentajeAjusteInput.value = '';
        
        })


        porcentajeAcuInput.addEventListener('input',function(e){
            if(((e.target.value)*1)<0 || ((e.target.value)*1)>100){
             
                e.target.value = '';
            }
            const porcentaje = (e.target.value)/100;
            calcularDatos(porcentaje,subsidioAcuInput, copagoAcuInput,totalAcuInput);
          
          
        })
        porcentajeAlcInput.addEventListener('input',function(e){
            if(((e.target.value)*1)<0 || ((e.target.value)*1)>100){
             
                e.target.value = '';
            }
            const porcentaje = (e.target.value)/100;
            calcularDatos(porcentaje,subsidioAlcInput, copagoAlcInput,totalAlcInput);
    
          
        })
        porcentajeAseoInput.addEventListener('input',function(e){
            if(((e.target.value)*1)<0 || ((e.target.value)*1)>100){
             
                e.target.value = '';
            }
            const porcentaje = (e.target.value)/100;
            calcularDatos(porcentaje,subsidioAseoInput, copagoAseoInput,totalAseoInput);
          
        })




        porcentajeAjusteInput.addEventListener('input',calcularValorAjuste)
        
        ajusteInput.addEventListener('input',function(e){
            calcularPorcentajeAjuste()
            formaterValor(e);
         
          
        })

        /* Funciones */

        function formaterValor(e){
            const tarifa_plena = e.target.value;
            let tarifa_plena_sin_formato = parseFloat(tarifa_plena.replace(/,/g, ''));
            if(isNaN(tarifa_plena_sin_formato)){
                tarifa_plena_sin_formato = '';
            }
            const tarifa_plena_formateada =  tarifa_plena_sin_formato.toLocaleString('en');
            e.target.value = tarifa_plena_formateada ;
        }

        function calcularValorAjuste(){

            if(((porcentajeAjusteInput.value)*1)<0 || ((porcentajeAjusteInput.value)*1)>100){
                porcentajeAjusteInput.value = '';
            }
            if(copagoInput.value!='' && porcentajeAjusteInput.value !='' && copagoInput.value!=0){
                const copago = parseFloat(copagoInput.value.replace(/,/g, ''));
                const ajuste = ((porcentajeAjusteInput.value/100)*copago).toFixed(2);
                ajusteInput.value = (parseFloat(ajuste)).toLocaleString('en');
 
              
            }else{
                ajusteInput.value = '';
                porcentajeAjusteInput.value = '';

            }
        }
    
        function calcularPorcentajeAjuste(){
      
            if(copagoInput.value!='' && ajusteInput.value !='' && copagoInput.value!=0){
                const valor_ajuste = parseFloat(ajusteInput.value.replace(/,/g, ''));
                const valor_copago = parseFloat(copagoInput.value.replace(/,/g, ''));

                if(valor_ajuste>valor_copago){
                    ajusteInput.value = '';
                    porcentajeAjusteInput.value = '';
                    return;
                }

                const porcentaje_ajuste = (((valor_ajuste*100)/valor_copago).toFixed(2))*1;
                porcentajeAjusteInput.value = porcentaje_ajuste;
              
            }else{
                ajusteInput.value = '';
                porcentajeAjusteInput.value = '';

            }
            
        }

        function revisarValores(){
           
            calcularDatos(porcentajeAcuInput.value/100,subsidioAcuInput, copagoAcuInput,totalAcuInput);
            calcularDatos(porcentajeAlcInput.value/100,subsidioAlcInput, copagoAlcInput,totalAlcInput);
            calcularDatos(porcentajeAseoInput.value/100,subsidioAseoInput, copagoAseoInput,totalAseoInput);
     
        }
     
        function calcularDatos(porcentaje,subsidioInputRef, copagoInputRef,totalInputRef){
    
            const tarifa_plena_formateada = tarifaPlenaInput.value;
            const porcentaje_subsidio = (porcentajeSubsidioInput.value)/100;
          
            if(tarifa_plena_formateada!='' && porcentaje_subsidio!='' && porcentaje !=''){
               
                const tarifa_plena = parseFloat(tarifa_plena_formateada.replace(/,/g, ''));
                const subsidio = tarifa_plena*porcentaje_subsidio*porcentaje;
                const copago = (((tarifa_plena*(1-porcentaje_subsidio))*porcentaje).toFixed(2))*1;
                const totalValor = tarifa_plena*porcentaje;
                const total = totalValor.toLocaleString('en');
              
                insertarDatos(subsidio, copago, subsidioInputRef, copagoInputRef,totalInputRef,total);
            }else{
                subsidioInputRef.value=0;
                copagoInputRef.value=0;
                totalInputRef.value=0;
            }

        }

       

        function calcularSubsidioCopago(){
            const tarifa_plena_formateada = tarifaPlenaInput.value;
            const porcentaje_subsidio = (porcentajeSubsidioInput.value)/100;
       
            if(tarifa_plena_formateada!='' && porcentaje_subsidio!=''){
                const tarifa_plena = parseFloat(tarifa_plena_formateada.replace(/,/g, ''));
                const subsidio = tarifa_plena*porcentaje_subsidio;
                const copago = ((tarifa_plena*(1-porcentaje_subsidio)).toFixed(2))*1;
          
                insertarDatos(subsidio, copago, subsidioInput, copagoInput);
            }else{
                subsidioInput.value = '' ;
                copagoInput.value = '';
            }
        }

        function insertarDatos(subsidio, copago, referencia1, referencia2, referencia3=[] , total= ''){
            if(total!=''){
               referencia3.value = total;
            }
            const valor_subsidio = subsidio.toLocaleString('en');
            const valor_copago = copago.toLocaleString('en');
            referencia1.value = valor_subsidio
            referencia2.value = valor_copago
           
        }
        
    }
})();