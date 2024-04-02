

(function(){
  
    const tablaRegistrados = document.querySelector('#tablaRegistrados');
 
    if(tablaRegistrados){

        const downloadUsersXlsxBtn = document.querySelector('#downloadUsersXlsx');
        downloadUsersXlsxBtn.addEventListener('click', downloadUsersXlsx)

      

        $('#tablaRegistrados').on('click', '#btn_eliminar_registrado', function(e) {
            // Manejar el evento aquí
            leerAccion(e);
            // Resto del código
        });
       
        let tablaRegistradosData;
        mostrarRegistrados();
        verificarCargaTabla();

        function verificarCargaTabla(){
            tablaRegistradosData.on('draw', eventosClickEditar);
        }
        function eventosClickEditar(){
          
            const btnEliminar = document.querySelectorAll('#btn_eliminar_registrado');
            btnEliminar.forEach(btnEliminar=>{
              
                btnEliminar.addEventListener('click', function(e){
                    // const registradoId = e.currentTarget.dataset.registradoId;
                    leerAccion(e);
                })
            })
        }
        // tablaRegistrados.addEventListener('click',function(e){
        //     leerAccion(e);
        // })
        function leerAccion( e){
       
            if(e.currentTarget.id=='btn_eliminar_registrado'){
                eliminarRegistrado( e);
            }
            
        }
        function eliminarRegistrado(e){
            const registrado = e.currentTarget.parentNode.parentNode.parentNode.children[1].textContent;
            const id = e.currentTarget.dataset.registradoId;
            
            Swal.fire({
                title: `Esta seguro que desea eliminar el estrato ${registrado}?`,
                text:'Esta accion no se puede deshacer',
                icon: 'question',
                showDenyButton: true,
                confirmButtonText: 'Aceptar',
                denyButtonText: `Cancelar`,
              }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    enviarInformacionEliminar(id);
                    
                  //Swal.fire('Saved!', '', 'success')
                }
              })
        }
        async function enviarInformacionEliminar(id){
       
            url = `/api/registrados/eliminar?id=${id}`;
            try {
                const respuesta = await fetch(url)
                const resultado = await respuesta.json();
               
            
                if(resultado.tipo=='error'){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: resultado.mensaje,
        
                    })
                    return;
                }
                Swal.fire({
                    icon: 'success',
                  
                    text: resultado.mensaje,
                    
                })
                tablaRegistradosData.ajax.reload(); 
            } catch (error) {
                console.log(error)
            }
        }




        


        function mostrarRegistrados(){
            $("#tablaRegistrados").dataTable().fnDestroy(); //por si me da error de reinicializar
    
            tablaRegistradosData = $('#tablaRegistrados').DataTable({
                ajax: '/api/registrados',
                "deferRender":true,
                "retrieve":true,
                "proccesing":true,
                responsive:true
            });
            // $.ajax({
            //     url:'/api/registrados',
            //     success:function(req){
            //         console.log(req)
            //     },
            //     error:function(error){
            //         console.log(error.responseText);
            //     }
                
            // })
        }

     async function downloadUsersXlsx(){

       
        const url = `/api/registrados/download`;

        try {
            
            const respuesta = await fetch(url)
          
           
            const resultado = await respuesta.json();

            console.log(resultado)


   // Crear un nuevo libro de Excel
   var workbook = XLSX.utils.book_new();
        
   // Convertir el JSON en una hoja de Excel
   var worksheet = XLSX.utils.json_to_sheet(resultado);

   // Agregar la hoja de Excel al libro
   XLSX.utils.book_append_sheet(workbook, worksheet, "Datos");

   // Guardar el libro como un archivo XLSX
   XLSX.writeFile(workbook, "registrados.xlsx");
        
          
            // tablaRegistradosData.ajax.reload(); 
        } catch (error) {
            console.log(error)
        }

 
            // var jsonData = [
            //     { Nombre: "Juan", Apellido: "Pérez", Edad: 30 },
            //     { Nombre: "María", Apellido: "Gómez", Edad: 25 },
            //     { Nombre: "Carlos", Apellido: "López", Edad: 35 }
            // ];
        
         
        }
        
    }
})();