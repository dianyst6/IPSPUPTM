<div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="editmodallabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header bg-warning text-dark">
        <h1 class="modal-title fs-5" id="editmodalLabel">
          <i class="fas fa-user-edit"></i> Datos de Comunidad UPTM
        </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form action="/IPSPUPTM/app/comunidaduptm/actualizar/actualizar.php" method="post">

          <div class="container">
            <div class="mb-3">
              <label for="cedula" class="form-label fw-bold">Cédula</label>
              <input type="text" id="cedula" name="cedula" class="form-control bg-light" pattern="[0-9]{1,8}"
                title="Solo se permiten hasta 8 números" maxlength="8">
            </div>

            <div class="mb-3">
              <label for="nombre" class="form-label fw-bold">Nombre</label>
              <input type="text" id="nombre" name="nombre" class="form-control bg-light" maxlength="50">
            </div>

            <div class="mb-3">
              <label for="apellido" class="form-label fw-bold">Apellido</label>
              <input type="text" id="apellido" name="apellido" class="form-control bg-light">
            </div>
            <div class="mb-3">
              <label for="telefono" class="form-label fw-bold">Telefono</label>
              <input type="text" id="telefono" name="telefono" class="form-control bg-light" maxlength="11"
                pattern="04[0-9]{9}" title="El teléfono debe tener 11 dígitos y comenzar con 04 (ej: 04121234567)">
              <div id="telefonoFeedback" class="text-danger mt-1" style="display:none; font-size: 0.875em;">El teléfono
                debe comenzar con 04 y tener exactamente 11 dígitos.</div>
            </div>
          </div>

          <div class="modal-footer px-0 pb-0">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-warning">Guardar Cambios</button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>

<script>
  document.body.addEventListener('input', function (event) {
    if (!event.target) return;

    // Validación y sanitización del campo teléfono en editmodal comunidad (solo números, máx 11, debe comenzar con 04)
    if (event.target.id === 'telefono') {
      let input = event.target;
      input.value = input.value.replace(/\D/g, '').slice(0, 11);
      const feedback = document.getElementById('telefonoFeedback');
      if (input.value.length > 0 && !input.value.startsWith('04')) {
        feedback.style.display = 'block';
        input.classList.add('is-invalid');
      } else {
        feedback.style.display = 'none';
        input.classList.remove('is-invalid');
      }
    }
  });
</script>