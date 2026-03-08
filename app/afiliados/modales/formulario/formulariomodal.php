<div class="modal fade" id="formulariomodal" tabindex="-1" aria-labelledby="formulariomodallabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="FormularioModalLabel">Formulario de registro de Afiliado</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="/IPSPUPTM/app/afiliados/modales/formulario/guardar.php" method="post">
          <div class="container">
            
            <h6 class="text-primary fw-bold mb-3"><i class="fas fa-user me-2"></i>Datos Personales</h6>
            <div class="row mb-3">
              <div class="col-md-6 col-12">
                <label for="cedula" class="form-label">Cédula</label>
                <input type="text" name="cedula" id="cedula" class="form-control" required>
              </div>
              <div class="col-md-6 col-12">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6 col-12">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" name="apellido" id="apellido" class="form-control" required>
              </div>
              <div class="col-md-6 col-12">
                <label for="fechanacimiento" class="form-label">Fecha de Nacimiento</label>
                <input type="date" name="fechanacimiento" id="fechanacimiento" class="form-control" required>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6 col-12">
                <label for="genero" class="form-label">Género</label>
                <select name="genero" id="genero" class="form-select" required>
                  <option value="">Seleccionar...</option>
                  <option value="masculino">Masculino</option>
                  <option value="femenino">Femenino</option>
                </select>
              </div>
              <div class="col-md-6 col-12">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" name="telefono" id="telefono" class="form-control" required>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6 col-12">
                <label for="correo" class="form-label">Correo Electrónico</label>
                <input type="email" name="correo" id="correo" class="form-control" required>
              </div>
              <div class="col-md-6 col-12">
                <label for="ocupacion" class="form-label">Ocupación</label>
                <input type="text" name="ocupacion" id="ocupacion" class="form-control" required>
              </div>
            </div>

            <hr class="my-4">
            <h6 class="text-success fw-bold mb-3"><i class="fas fa-file-contract me-2"></i>Información del Contrato</h6>
            
            <div class="row mb-3">
              <div class="col-md-8 col-12">
                <label for="id_planes_contrato" class="form-label">Plan de Salud</label>
                <select name="id_planes_contrato" id="id_planes_contrato" class="form-select" required onchange="vincularPrecioPlan()">
                  <option value="">Seleccione un plan...</option>
                  <?php
                  include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';
                  $planes = mysqli_query($conn, "SELECT ID_planes, nombre_plan, precio FROM planes");
                  while($p = mysqli_fetch_assoc($planes)) {
                      echo "<option value='{$p['ID_planes']}' data-precio='{$p['precio']}'>{$p['nombre_plan']}</option>";
                  }
                  ?>
                </select>
              </div>
              <div class="col-md-4 col-12">
                <label for="monto_total" class="form-label">Monto del Contrato ($)</label>
                <input type="number" step="0.01" name="monto_total" id="monto_total_input" class="form-control" required>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-6 col-12">
                <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required value="<?php echo date('Y-m-d'); ?>">
              </div>
              <div class="col-md-6 col-12">
                <label for="fecha_fin" class="form-label">Fecha de Finalización</label>
                <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" required>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-4 col-12">
                <label for="frecuencia_pago" class="form-label">Frecuencia de Pago</label>
                <select name="frecuencia_pago" id="frecuencia_pago" class="form-select" required>
                  <option value="Mensual">Mensual</option>
                  <option value="Trimestral">Trimestral</option>
                  <option value="Semestral">Semestral</option>
                  <option value="Anual">Anual</option>
                </select>
              </div>
              <div class="col-md-4 col-12">
                <label for="dia_pago_mensual" class="form-label">Día de pago (1-31)</label>
                <input type="number" name="dia_pago_mensual" id="dia_pago_mensual" class="form-control" min="1" max="31" required>
              </div>
              <div class="col-md-4 col-12">
                <label for="estado_contrato" class="form-label">Estado</label>
                <select name="estado_contrato" id="estado_contrato" class="form-select">
                  <option value="Activo">Activo</option>
                  <option value="Pendiente">Pendiente</option>
                </select>
              </div>
            </div>

          </div>
          <div class="modal-footer px-0 pb-0 pt-3">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Guardar Registro Completo</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
function vincularPrecioPlan() {
    const select = document.getElementById('id_planes_contrato');
    const montoInput = document.getElementById('monto_total_input');
    const precio = select.options[select.selectedIndex].getAttribute('data-precio');
    montoInput.value = precio ? precio : '';
}
</script>