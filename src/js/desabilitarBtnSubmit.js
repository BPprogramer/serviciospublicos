

(function(){
 
    const formulario =  document.querySelector('.formulario');

    if(formulario){
        
        const btnSubmit = document.querySelector('.formulario [type="submit"]');
        
        btnSubmit.addEventListener('click',deshabilitarBoton);
        function deshabilitarBoton(e){
            setTimeout(()=>{
                e.target.disabled = true;
                e.target.classList.add('formulario__submit--deshabilitado');
            },0)
           
        }
    }
})();