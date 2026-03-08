<div class="modal fade" id="formulariomodal" tabindex="-1" aria-labelledby="formulariomodallabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="FormularioModalLabel">Registrar nueva cita</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="form-registro-cita-general">
          
          <div class="mb-3">
            <label for="tipo_paciente_selector" class="form-label fw-bold">Tipo de Paciente</label>
            <select name="tipo_paciente" id="tipo_paciente_selector" class="form-select border-primary" required>
              <option value="" selected disabled>Seleccione una opción...</option>
              <option value="interno">Afiliado / Beneficiario</option>
              <option value="externo">Comunidad UPTM (Externo)</option>
            </select>
          </div>

          <hr>

          <div id="campos-interno" style="display: none;">
            <div class="mb-3">
              <label for="paciente_search" class="form-label">Escriba Cédula o Nombre del Paciente</label>
              <input class="form-control" list="lista-pacientes" id="paciente_search" placeholder="Escriba para buscar...">
              <input type="hidden" name="id_paciente" id="id_paciente">
              
              <datalist id="lista-pacientes">
                <?php
                $sql_pacientes = "
                SELECT a.id AS id_pac_val, p.cedula, CONCAT(p.nombre, ' ', p.apellido, ' - (Afiliado)') AS nombre_completo FROM afiliados a JOIN persona p ON a.cedula = p.cedula
                UNION
                SELECT b.id AS id_pac_val, p.cedula, CONCAT(p.nombre, ' ', p.apellido, ' - (Beneficiario)') AS nombre_completo FROM beneficiarios b JOIN persona p ON b.cedula = p.cedula
                ORDER BY nombre_completo ASC";
                $result_pacientes = $conn->query($sql_pacientes);
                while ($row = $result_pacientes->fetch_assoc()) {
                  // Guardamos el ID en el data-id y mostramos Cedula + Nombre en el value
                  echo '<option data-id="' . $row['id_pac_val'] . '" value="' . $row['cedula'] . ' | ' . $row['nombre_completo'] . '"></option>';
                }
                ?>
              </datalist>
              <small class="text-muted">Seleccione una opción de la lista desplegable.</small>
            </div>
          </div>

          <div id="campos-externo" style="display: none;">
            <div class="row">
              <div class="col-md-4 mb-3">
                <label for="cedula_ext" class="form-label">Cédula</label>
               <input type="text" 
              name="cedula_ext" 
               id="cedula_ext" 
               class="form-control" 
               oninput="this.value = this.value.replace(/[^0-9]/g, '')" 
               placeholder="Ej: 25123456">
              </div>
              <div class="col-md-4 mb-3">
                <label for="nombre_ext" class="form-label">Nombre</label>
                <input type="text" name="nombre_ext" id="nombre_ext" class="form-control">
              </div>
              <div class="col-md-4 mb-3">
                <label for="apellido_ext" class="form-label">Apellido</label>
                <input type="text" name="apellido_ext" id="apellido_ext" class="form-control">
              </div>
            </div>
          </div>

          <div id="campos-comunes" style="display: none;">
            <div class="mb-3">
              <label for="id_especialidad" class="form-label">Especialidad</label>
              <select name="id_especialidad" id="id_especialidad" class="form-select" required>
                <option value="" selected disabled>Seleccionar...</option>
                <?php
                $sql_especialidades = "SELECT id_especialidad, nombre_especialidad FROM especialidades ORDER BY nombre_especialidad ASC";
                $result_espe = $conn->query($sql_especialidades);
                while ($row_e = $result_espe->fetch_assoc()) {
                  echo '<option value="' . $row_e['id_especialidad'] . '">' . $row_e['nombre_especialidad'] . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="fecha_cita" class="form-label">Fecha y Hora</label>
              <input type="datetime-local" name="fecha_cita" id="fecha_cita" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="descripcion" class="form-label">Descripción / Motivo</label>
              <textarea name="descripcion" id="descripcion" class="form-control" rows="2" required></textarea>
            </div>
          </div>

          <div class="modal-footer px-0 pb-0">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" id="btn-guardar-cita" class="btn btn-primary" style="display: none;">Guardar Cita</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="/IPSPUPTM/assets/js/accionescitas.js"></script>
<script>
(function() {
    const setupFormulario = () => {
        const form = document.getElementById('form-registro-cita-general');
        const selector = document.getElementById('tipo_paciente_selector');
        
        // Contenedores
        const dInterno = document.getElementById('campos-interno');
        const dExterno = document.getElementById('campos-externo');
        const dComunes = document.getElementById('campos-comunes');
        const bGuardar = document.getElementById('btn-guardar-cita');
        
        // Inputs Internos
        const iSearch = document.getElementById('paciente_search');
        const iHidden = document.getElementById('id_paciente');
        const dList   = document.getElementById('lista-pacientes');

        // Inputs Externos
        const inputCedula = document.getElementById('cedula_ext');
        const inputNombre = document.getElementById('nombre_ext');
        const inputApellido = document.getElementById('apellido_ext');

        if (!selector) return;

        // --- 1. LÓGICA DE MOSTRAR/OCULTAR Y REQUERIDOS ---
        selector.addEventListener('change', function() {
            const esInterno = (this.value === 'interno');
            
            dComunes.style.display = 'block';
            bGuardar.style.display = 'block';
            dInterno.style.display = esInterno ? 'block' : 'none';
            dExterno.style.display = esInterno ? 'none' : 'block';

            // Ajustar requeridos de forma masiva
            const inputsExt = dExterno.querySelectorAll('input');
            inputsExt.forEach(input => {
                if (esInterno) {
                    input.removeAttribute('required');
                    input.value = ""; // Limpiar si cambia de opinión
                } else {
                    input.setAttribute('required', 'required');
                }
            });

            if (esInterno) {
                iSearch.setAttribute('required', 'required');
            } else {
                iSearch.removeAttribute('required');
                iSearch.value = "";
                iHidden.value = "";
            }
        });

        // --- 2. BÚSQUEDA EN TIEMPO REAL (EXTERNOS) ---
        inputCedula.addEventListener('blur', function() {
            const cedula = this.value.trim();
            if (cedula.length >= 6) { // Solo busca si la cédula tiene un largo razonable
                fetch(`/IPSPUPTM/app/citas/buscar_externo.php?cedula=${cedula}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            inputNombre.value = data.nombre;
                            inputApellido.value = data.apellido;
                            alertify.success("Paciente encontrado en el historial.");
                        }
                    })
                    .catch(err => console.error("Error buscando externo:", err));
            }
        });

        // --- 3. DETECTAR ID DEL DATALIST (INTERNOS) ---
        iSearch.addEventListener('input', function() {
            const val = this.value;
            const options = dList.options;
            iHidden.value = ""; 
            for (let i = 0; i < options.length; i++) {
                if (options[i].value === val) {
                    iHidden.value = options[i].getAttribute('data-id');
                    break;
                }
            }
        });

        // --- 4. ENVÍO DE DATOS ---
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Validación: Si es interno y el hidden está vacío, no eligió de la lista
            if (selector.value === 'interno' && !iHidden.value) {
                alertify.error("Por favor, seleccione un paciente de la lista de Afiliados/Beneficiarios.");
                return;
            }

            const formData = new FormData(this);

            fetch('/IPSPUPTM/app/citas/modales/formulario/guardar.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alertify.success(data.message);
                    bootstrap.Modal.getInstance(document.getElementById('formulariomodal')).hide();
                    form.reset();
                    // Ocultar campos de nuevo
                    dComunes.style.display = 'none';
                    bGuardar.style.display = 'none';
                    setTimeout(() => location.reload(), 1000);
                } else {
                    alertify.error(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alertify.error("Error de conexión al guardar");
            });
        });
    };

    setupFormulario();
})();
</script>