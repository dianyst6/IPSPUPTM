document.addEventListener("DOMContentLoaded", function() {
    
    // === Carga Dinámica de Select2 ===
    var script = document.createElement('script');
    script.src = '/IPSPUPTM/assets/select2/js/select2.min.js'; // Ajusta esta ruta a tu carpeta real
    script.onload = function() {
        // Inicializar Select2 al abrir el modal de registro
        $('#formulariomodal').on('shown.bs.modal', function () {
            $('#id_paciente_select2').select2({
                dropdownParent: $('#formulariomodal'),
                placeholder: "Busque por cédula o nombre...",
                width: '100%'
            });
        });
    };
    document.head.appendChild(script);

    // === Lógica de Cambio de Tipo (Interno / Externo) ===
    const selectorTipo = document.getElementById('tipo_paciente_selector');
    if (selectorTipo) {
        selectorTipo.addEventListener('change', function() {
            const esInterno = (this.value === 'interno');
            document.getElementById('campos-comunes').style.display = 'block';
            document.getElementById('btn-guardar-cita').style.display = 'block';
            
            document.getElementById('campos-interno').style.display = esInterno ? 'block' : 'none';
            document.getElementById('campos-externo').style.display = esInterno ? 'none' : 'block';

            // Limpiar y ajustar requeridos
            if (!esInterno) {
                $('#id_paciente_select2').val(null).trigger('change');
            }
        });
    }

    // === Modal de Eliminación ===
    const eliminamodal = document.getElementById('eliminamodal');
    if (eliminamodal) {
        eliminamodal.addEventListener('shown.bs.modal', event => {
            const idCita = event.relatedTarget.getAttribute('data-bs-idcita');
            eliminamodal.querySelector('#id_cita').value = idCita;
        });
    }

    // === Modal de Edición ===
    const editmodal = document.getElementById('editmodal');
    if (editmodal) {
        editmodal.addEventListener('shown.bs.modal', event => {
            const idCita = event.relatedTarget.getAttribute('data-bs-idcita');
            cargarDatosModal(editmodal, idCita);
        });
    }

    // === Filtrado de Tabla ===
    const searchInput = document.getElementById("search");
    if (searchInput) {
        searchInput.addEventListener("keyup", function() {
            const term = this.value.toLowerCase();
            const rows = document.querySelectorAll('.table tbody tr');
            rows.forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });
    }
});

// Función para cargar datos al editar
function cargarDatosModal(modal, id_cita) {
    let formData = new FormData();
    formData.append('id_cita', id_cita);

    fetch("/IPSPUPTM/app/citas/getcitas.php", {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.length > 0) {
            const cita = data[0];
            modal.querySelector('#id_cita_editar').value = cita.id_cita;
            modal.querySelector('#id_especialidad_editar').value = cita.id_especialidad;
            modal.querySelector('#fecha_cita_editar').value = cita.fecha_cita;
            modal.querySelector('#descripcion_editar').value = cita.descripcion;

            if (cita.tipo_origen === 'externo') {
                modal.querySelector('#campos_externos_editar').style.display = 'block';
                modal.querySelector('#campos_internos_editar').style.display = 'none';
                modal.querySelector('#cedula_ext_editar').value = cita.cedula_ext;
                modal.querySelector('#nombre_ext_editar').value = cita.nombre_ext;
                modal.querySelector('#apellido_ext_editar').value = cita.apellido_ext;
            } else {
                modal.querySelector('#campos_externos_editar').style.display = 'none';
                modal.querySelector('#campos_internos_editar').style.display = 'block';
                modal.querySelector('#id_paciente_editar').value = cita.id_paciente;
            }
        }
    })
    .catch(err => console.error("Error al cargar:", err));
}