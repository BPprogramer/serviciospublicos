

(function(){
    const alertaExitoFormulario = document.querySelector('.alerta__exito');
    const formulario = document.querySelector('.formulario');
    if(alertaExitoFormulario){
        formulario.style.display='none';
        setTimeout(()=>{
            alertaExitoFormulario.remove();
            redireccionar();

        },3000);

        function redireccionar(){
            const urlActual = window.location.href;
            if(urlActual.includes('/servicios/admin/usuarios/')){
                window.location.href = '/servicios/admin/usuarios';
            }
            if(urlActual.includes('/servicios/admin/estratos/')){
                window.location.href = '/servicios/admin/estratos';
            }
            if(urlActual.includes('/servicios/admin/registrados/')){
                window.location.href = '/servicios/admin/registrados';
            }
            if(urlActual.includes('/servicios/admin/cajas/')){
                window.location.href = '/servicios/admin/cajas';
            }
        }
    }

})();



  
