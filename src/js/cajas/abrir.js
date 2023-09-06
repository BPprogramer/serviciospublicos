(function(){
    const caja = document.querySelector('#caja');
    if(caja){
        const efectivoInicialInput = document.querySelector('#efectivo_inicial');
        efectivoInicialInput.addEventListener('input',function(e){
            formaterValor(e)
        })
        function formaterValor(e){
        
        
            const efectivo_inicial = e.target.value;
            let efectivo_inicial_sin_formato = parseFloat(efectivo_inicial.replace(/,/g, ''));
            if(isNaN(efectivo_inicial_sin_formato)){
                efectivo_inicial_sin_formato = '';
            }
            const efectivo_inicial_formateada =  efectivo_inicial_sin_formato.toLocaleString('en');
            e.target.value = efectivo_inicial_formateada ;
        }
    }

})();