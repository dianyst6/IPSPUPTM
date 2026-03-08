<div class="modal fade" id="formulariomodal" tabindex="-1" aria-labelledby="formulariomodallabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">Registrar Historia Médica</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="form-registro-historia" action="/IPSPUPTM/app/historias_medicas/modales/formulario/guardar.php" method="POST">
          
          <input type="hidden" name="ci_medico" id="ci_medico_input" value="14107471">

          <div class="mb-3">
            <label class="form-label fw-bold">Tipo de Paciente</label>
            <select name="tipo_paciente" id="tipo_paciente_selector" class="form-select border-primary" required>
              <option value="" selected disabled>Seleccione...</option>
              <option value="interno">Afiliado / Beneficiario</option>
              <option value="externo">Comunidad UPTM (Externo)</option>
            </select>
          </div>

          <div id="campos-interno" style="display: none;">
            <div class="mb-3">
              <label class="form-label">Cédula o Nombre (Afiliado/Beneficiario)</label>
              <input class="form-control" list="lista-pacientes" id="paciente_search" placeholder="Buscar por CI o Nombre...">
              <input type="hidden" name="ci_paciente" id="ci_paciente_hidden">
              <datalist id="lista-pacientes">
                <?php
                // Consulta a la tabla 'persona'
                $sql = "SELECT cedula, fechanacimiento, CONCAT(nombre, ' ', apellido) as nombre_completo FROM persona";
                $res = $conn->query($sql);
                while($r = $res->fetch_assoc()){
                  echo '<option data-ci="'.$r['cedula'].'" data-fecha="'.$r['fechanacimiento'].'" value="'.$r['cedula'].' | '.$r['nombre_completo'].'"></option>';
                }
                ?>
              </datalist>
            </div>
          </div>

          <div id="campos-externo" style="display: none;">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Cédula Externo</label>
                <input type="number" name="cedula_ext" id="cedula_ext" class="form-control">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Nombre y Apellido</label>
                <input type="text" name="nombre_ext" id="nombre_ext" class="form-control">
              </div>
            </div>
          </div>

          <div id="campos-comunes" style="display: none;">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Fecha Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento_input" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Edad</label>
                    <input type="number" name="edad" id="edad_input" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Fecha Consulta</label>
                    <input type="date" name="fecha_consulta" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Dirección</label>
              <input type="text" name="direccion" class="form-control" required placeholder="Av. Principal, Edif...">
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Motivo de Consulta</label>
              <textarea name="motivo_consulta" class="form-control" rows="2" required></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Enfermedad Actual</label>
              <textarea name="enfermedad_actual" class="form-control" rows="2" required></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Antecedentes Familiares</label>
                    <textarea name="antecedentes_familiares" class="form-control" rows="2"></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Antecedentes Personales</label>
                    <textarea name="antecedentes_personales" class="form-control" rows="2"></textarea>
                </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Información Adicional (Opcional)</label>
              <textarea name="info_adicional" class="form-control" rows="2"></textarea>
            </div>
          </div>

          <div class="modal-footer px-0 pb-0">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary" id="btn_guardar_historia" style="display:none;">Guardar Historia</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    const selector = document.getElementById('tipo_paciente_selector');
    const dInterno = document.getElementById('campos-interno');
    const dExterno = document.getElementById('campos-externo');
    const dComunes = document.getElementById('campos-comunes');
    const bGuardar = document.getElementById('btn_guardar_historia');
    
    const iFechaNac = document.getElementById('fecha_nacimiento_input');
    const iEdad = document.getElementById('edad_input');
    const form = document.getElementById('form-registro-historia');

    // 1. Lógica para mostrar/ocultar secciones según tipo de paciente
    selector.addEventListener('change', function() {
        const opcion = this.value;
        dComunes.style.display = 'block';
        bGuardar.style.display = 'block';

        if (opcion === 'interno') {
            dInterno.style.display = 'block';
            dExterno.style.display = 'none';
            iFechaNac.setAttribute('readonly', 'readonly');
        } else {
            dInterno.style.display = 'none';
            dExterno.style.display = 'block';
            iFechaNac.value = "";
            iEdad.value = "";
            iFechaNac.removeAttribute('readonly');
        }
    });

    // 2. Buscador de Pacientes Internos (Datalist)
    document.getElementById('paciente_search').addEventListener('input', function() {
        const datalist = document.getElementById('lista-pacientes');
        const options = datalist.options;
        const hiddenCi = document.getElementById('ci_paciente_hidden');

        for (let i = 0; i < options.length; i++) {
            if (options[i].value === this.value) {
                const cedula = options[i].getAttribute('data-ci');
                const fecha = options[i].getAttribute('data-fecha');
                
                hiddenCi.value = cedula;
                iFechaNac.value = fecha;
                
                if (fecha) {
                    const hoy = new Date();
                    const cumple = new Date(fecha);
                    let edad = hoy.getFullYear() - cumple.getFullYear();
                    const m = hoy.getMonth() - cumple.getMonth();
                    if (m < 0 || (m === 0 && hoy.getDate() < cumple.getDate())) {
                        edad--;
                    }
                    iEdad.value = edad;
                }
                break;
            }
        }
    });

    // 3. Cálculo de edad para externos
    iFechaNac.addEventListener('change', function() {
        if (this.value && !this.readOnly) {
            const hoy = new Date();
            const cumple = new Date(this.value);
            let edad = hoy.getFullYear() - cumple.getFullYear();
            const m = hoy.getMonth() - cumple.getMonth();
            if (m < 0 || (m === 0 && hoy.getDate() < cumple.getDate())) {
                edad--;
            }
            iEdad.value = edad;
        }
    });

    // 4. Envío de datos por AJAX
    form.addEventListener('submit', function(e) {
        e.preventDefault(); 

        const formData = new FormData(this);
        const ruta = this.getAttribute('action');

        fetch(ruta, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json()) // Cambiado a JSON para coincidir con guardar.php
        .then(data => {
            if (data.success === true) {
                alert("✅ " + data.message);
                location.reload(); 
            } else {
                alert("⚠️ Error: " + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("❌ Error al procesar la solicitud.");
        });
    });
});
</script>