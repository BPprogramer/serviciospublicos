(function(){

    const form = document.querySelector('.js-egreso-form');
    if(!form) return;

    const tbody = form.querySelector('#js-lineas-egreso');
    const btnAgregar = form.querySelector('.js-agregar-linea');
    const selectBase = form.querySelector('.js-cuenta-select');

    if(!tbody || !btnAgregar || !selectBase) return;

    let contador = tbody.querySelectorAll('tr').length;
    const opcionesHTML = selectBase.innerHTML;

    // ===============================
    // AGREGAR LINEA
    // ===============================
    btnAgregar.addEventListener('click', function(){

        const fila = document.createElement('tr');

        fila.innerHTML = `
            <td>
                <select name="cuentas[${contador}][cuenta_id]" class="formulario__select">
                    ${opcionesHTML}
                </select>
            </td>
            <td>
                <input type="text" name="cuentas[${contador}][debito]" class="formulario__input js-debito">
            </td>
            <td>
                <input type="text" name="cuentas[${contador}][credito]" class="formulario__input js-credito">
            </td>
            <td>
                <button type="button" class="btnEliminarLinea">X</button>
            </td>
        `;

        tbody.appendChild(fila);
        contador++;
    });

    // ===============================
    // ELIMINAR LINEA
    // ===============================
    form.addEventListener('click', function(e){

        if(e.target.classList.contains('btnEliminarLinea')){

            const filas = tbody.querySelectorAll('tr');

            if(filas.length <= 1){
                return;
            }

            e.target.closest('tr').remove();
            recalcularIndices();
            calcularTotales();
        }
    });

    // ===============================
    // RECALCULAR INDICES
    // ===============================
    function recalcularIndices(){

        const filas = tbody.querySelectorAll('tr');

        filas.forEach((fila, index) => {

            fila.querySelectorAll('select, input').forEach(input => {

                const name = input.getAttribute('name');

                if(name){
                    const nuevoName = name.replace(/cuentas\[\d+\]/, `cuentas[${index}]`);
                    input.setAttribute('name', nuevoName);
                }

            });

        });

        contador = filas.length;
    }

    // ===============================
    // CALCULAR TOTALES
    // ===============================
    form.addEventListener('input', function(e){

        if(e.target.classList.contains('js-debito') ||
           e.target.classList.contains('js-credito')){
            calcularTotales();
        }
    });

    function calcularTotales(){

        let totalDebito = 0;
        let totalCredito = 0;

        form.querySelectorAll('.js-debito').forEach(input => {
            totalDebito += parseFloat(input.value) || 0;
        });

        form.querySelectorAll('.js-credito').forEach(input => {
            totalCredito += parseFloat(input.value) || 0;
        });

        const spanDebito = form.querySelector('#js-total-debito');
        const spanCredito = form.querySelector('#js-total-credito');

        spanDebito.textContent = totalDebito.toFixed(2);
        spanCredito.textContent = totalCredito.toFixed(2);

        // Indicador visual si no cuadra
        if(totalDebito !== totalCredito){
            spanDebito.style.color = 'red';
            spanCredito.style.color = 'red';
        } else {
            spanDebito.style.color = 'green';
            spanCredito.style.color = 'green';
        }
    }

    // 🔥 IMPORTANTE: recalcular al cargar
    calcularTotales();

})();
