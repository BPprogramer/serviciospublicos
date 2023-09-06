

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
            if(urlActual.includes('/admin/usuarios/')){
                window.location.href = '/admin/usuarios';
            }
            if(urlActual.includes('/admin/estratos/')){
                window.location.href = '/admin/estratos';
            }
            if(urlActual.includes('/admin/registrados/')){
                window.location.href = '/admin/registrados';
            }
            if(urlActual.includes('/admin/cajas/')){
                window.location.href = '/admin/cajas';
            }
        }
    }

})();



  
