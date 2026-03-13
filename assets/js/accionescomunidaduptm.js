// Función para cargar datos en el modal (Solo Comunidad UPTM)
function cargarDatosModal(modal, cedula) {
    console.log("Cédula obtenida: ", cedula);

    // Seleccionamos los campos correctos (Asegúrate que los IDs coincidan en tu HTML)
    let inputcedula = modal.querySelector('#cedula_ext_editar') || modal.querySelector('#cedula');
    let inputnombre = modal.querySelector('#nombre_ext_editar') || modal.querySelector('#nombre');
    let inputapellido = modal.querySelector('#apellido_ext_editar') || modal.querySelector('#apellido');
    
    // CAMBIO: La ruta al PHP que creamos antes para buscar externos
    let url = "/IPSPUPTM/app/comunidaduptm/getcomunidad.php"; 

    let formData = new FormData();
    formData.append('cedula', cedula);

    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            console.error("Error: ", data.error);
            alertify.error(data.error);
        } else {
            // Llenamos solo lo necesario: Nombre, Apellido y Cédula
            if(inputcedula) inputcedula.value = data.cedula || '';
            if(inputnombre) inputnombre.value = data.nombre || '';
            if(inputapellido) inputapellido.value = data.apellido || '';
        }
    })
    .catch(error => console.error("Error de conexión: ", error));
}

// Evento para el Modal de Edición
let editmodal = document.getElementById('editmodal');
if (editmodal) {
    editmodal.addEventListener('shown.bs.modal', event => {
        let button = event.relatedTarget;
        let cedula = button.getAttribute('data-bs-cedula');
        cargarDatosModal(editmodal, cedula);
    });

    // Manejar el envío del formulario de actualización
    const formEditar = editmodal.querySelector('form');
    if (formEditar) {
        formEditar.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);

            // CAMBIO: Ruta hacia tu procesador de actualización de externos
            fetch('/IPSPUPTM/app/comunidaduptm/actualizar/actualizar.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alertify.success(data.message);
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    alertify.error(data.message);
                }
                bootstrap.Modal.getInstance(editmodal).hide();
            })
            .catch(error => alertify.error("Error al actualizar."));
        });
    }
}

// Evento para el Modal de Eliminación
const eliminamodal = document.getElementById('eliminamodal');
if (eliminamodal) {
    eliminamodal.addEventListener('shown.bs.modal', event => {
        const button = event.relatedTarget;
        const cedula = button.getAttribute('data-bs-cedula');
        // Buscamos el input oculto donde se guarda la cédula para borrar
        const cedulaInput = eliminamodal.querySelector('input[name="cedula"]');
        if (cedulaInput) cedulaInput.value = cedula;
    });

    const formEliminar = eliminamodal.querySelector('form');
    if (formEliminar) {
        formEliminar.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);

            // CAMBIO: Ruta hacia tu procesador de eliminación de externos
            fetch('/IPSPUPTM/app/comunidaduptm/eliminar/eliminar.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alertify.success(data.message);
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    alertify.error(data.message);
                }
                bootstrap.Modal.getInstance(eliminamodal).hide();
            })
            .catch(error => alertify.error("Error al eliminar."));
        });
    }
}

// Evento para el Formulario de Agregar
const formulariomodal = document.getElementById('formulariomodal');
if (formulariomodal) {
    const formAgregar = formulariomodal.querySelector('form');
    if (formAgregar) {
        formAgregar.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);

            fetch('/IPSPUPTM/app/comunidaduptm/formulario/guardar.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alertify.success(data.message);
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    alertify.error(data.message);
                }
                bootstrap.Modal.getInstance(formulariomodal).hide();
            })
            .catch(error => alertify.error("Error al guardar."));
        });
    }
}

// Filtro de búsqueda (Igual al que tenías, funciona perfecto)
const searchInput = document.getElementById('search');
if (searchInput) {
    searchInput.addEventListener('keyup', () => {
        const filter = searchInput.value.toLowerCase();
        const rows = document.querySelectorAll('.table tbody tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
}