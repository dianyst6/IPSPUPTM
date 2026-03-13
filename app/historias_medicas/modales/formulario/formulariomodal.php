<link href="/IPSPUPTM/assets/select2/css/select2.min.css" rel="stylesheet" />

<div class="modal fade" id="formulariomodal" tabindex="-1" aria-labelledby="formulariomodallabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable"> 
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">Registrar Historia Médica</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="max-height: 70vh;"> <form id="form-registro-historia" action="/IPSPUPTM/app/historias_medicas/modales/formulario/guardar.php" method="POST">
          
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
              <select name="ci_paciente" id="id_paciente_select2" class="form-select">
                <option value=""></option>
                <?php
                // Consulta optimizada para Select2
                $sql = "SELECT cedula, fechanacimiento, nombre, apellido FROM persona";
                $res = $conn->query($sql);
                while($r = $res->fetch_assoc()){
                  // Guardamos la fecha en un atributo data para usarla luego
                  echo '<option value="'.$r['cedula'].'" data-fecha="'.$r['fechanacimiento'].'">'.$r['cedula'].' | '.$r['nombre'].' '.$r['apellido'].'</option>';
                }
                ?>
              </select>
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
                    <input type="number" name="edad" id="edad_input" class="form-control" required readonly>
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
    
    // === CARGA DINÁMICA DE SELECT2 ===
    var script = document.createElement('script');
    script.src = '/IPSPUPTM/assets/select2/js/select2.min.js';
    script.onload = function() {
        $('#formulariomodal').on('shown.bs.modal', function () {
            $('#id_paciente_select2').select2({
                dropdownParent: $('#formulariomodal'),
                placeholder: "Buscar por CI o Nombre...",
                width: '100%'
            });
        });

        // Evento cuando se selecciona un paciente interno
        $('#id_paciente_select2').on('select2:select', function (e) {
            const fecha = e.params.data.element.dataset.fecha;
            const iFechaNac = document.getElementById('fecha_nacimiento_input');
            
            if (fecha) {
                iFechaNac.value = fecha;
                calcularEdad(fecha);
            }
        });
    };
    document.head.appendChild(script);

    // === LÓGICA DE INTERFAZ ===
    const selector = document.getElementById('tipo_paciente_selector');
    const dInterno = document.getElementById('campos-interno');
    const dExterno = document.getElementById('campos-externo');
    const dComunes = document.getElementById('campos-comunes');
    const bGuardar = document.getElementById('btn_guardar_historia');
    const iFechaNac = document.getElementById('fecha_nacimiento_input');
    const iEdad = document.getElementById('edad_input');

    selector.addEventListener('change', function() {
        dComunes.style.display = 'block';
        bGuardar.style.display = 'block';

        if (this.value === 'interno') {
            dInterno.style.display = 'block';
            dExterno.style.display = 'none';
            iFechaNac.setAttribute('readonly', 'readonly');
        } else {
            dInterno.style.display = 'none';
            dExterno.style.display = 'block';
            iFechaNac.removeAttribute('readonly');
            iFechaNac.value = "";
            iEdad.value = "";
        }
    });

    // Función para calcular edad
    function calcularEdad(fecha) {
        if (!fecha) return;
        const hoy = new Date();
        const cumple = new Date(fecha);
        let edad = hoy.getFullYear() - cumple.getFullYear();
        const m = hoy.getMonth() - cumple.getMonth();
        if (m < 0 || (m === 0 && hoy.getDate() < cumple.getDate())) {
            edad--;
        }
        iEdad.value = edad;
    }

    // Escuchar cambio manual de fecha para externos
    iFechaNac.addEventListener('change', function() {
        calcularEdad(this.value);
    });

    // Envío del Formulario
    document.getElementById('form-registro-historia').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch(this.getAttribute('action'), {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert("✅ " + data.message);
                location.reload();
            } else {
                alert("⚠️ Error: " + data.message);
            }
        })
        .catch(err => console.error('Error:', err));
    });
});
</script>