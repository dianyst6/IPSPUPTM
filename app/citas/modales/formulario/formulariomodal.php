<link href="/IPSPUPTM/assets/select2/css/select2.min.css" rel="stylesheet" />

<div class="modal fade" id="formulariomodal" tabindex="-1" aria-labelledby="formulariomodallabel" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-scrollable">    <div class="modal-content">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="FormularioModalLabel">Registrar nueva cita</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <div class="modal-body" style="max-height: 70vh;">
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
          <label for="id_paciente_select2" class="form-label fw-bold">Buscar Afiliado / Beneficiario</label>
        <select name="id_paciente" id="id_paciente_select2" class="form-select">
            <option value="">Seleccione un paciente...</option>
            <?php
            $sql_pacientes = "
                SELECT a.id, p.cedula, p.nombre, p.apellido, 'Afiliado' as tipo FROM afiliados a JOIN persona p ON a.cedula = p.cedula
                UNION
                SELECT b.id, p.cedula, p.nombre, p.apellido, 'Beneficiario' as tipo FROM beneficiarios b JOIN persona p ON b.cedula = p.cedula
                ORDER BY nombre ASC";
            $res = $conn->query($sql_pacientes);
            while ($f = $res->fetch_assoc()) {
                echo "<option value='{$f['id']}'>{$f['cedula']} | {$f['nombre']} {$f['apellido']} - ({$f['tipo']})</option>";
            }
            ?>
        </select>
        </div>
       </div>

          <div id="campos-externo" style="display: none;">
            <div class="row">
              <div class="col-md-4 mb-3">
                <label for="cedula_ext" class="form-label">Cédula</label>
                <input type="text" name="cedula_ext" id="cedula_ext" class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="Ej: 25123456">
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


<script src="/IPSPUPTM/assets/js/accionescitas.js"> </script>  