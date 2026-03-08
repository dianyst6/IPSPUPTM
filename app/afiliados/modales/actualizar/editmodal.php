<div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="editmodallabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editmodalLabel">Editar Afiliado</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form action="/IPSPUPTM/app/afiliados/modales/actualizar/actualizar.php" method="post">
          <input type="hidden" name="id" id="id">
          
          <!-- Contenedor para los campos -->
          <div class="container">
            <!-- Primer grupo: Cédula y Nombre -->
            <div class="row mb-3">
              <div class="col-md-6 col-12">
                <label for="cedula" class="form-label">Cédula</label>
                <input type="text" id="cedula" class="form-control" readonly name = "cedula">
              </div>
              <div class="col-md-6 col-12">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required>
              </div>
            </div>

            <!-- Segundo grupo: Apellido y Fecha -->
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

            <!-- Tercer grupo: Género y Teléfono -->
            <div class="row mb-3">
              <div class="col-md-6 col-12">
                <label for="genero" class="form-label">Género</label>
                <input type="text" id="genero" class="form-control" readonly name= "genero">
              </div>
              <div class="col-md-6 col-12">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" name="telefono" id="telefono" class="form-control" required>
              </div>
            </div>

            <!-- Cuarto grupo: Correo -->
            <div class="row mb-3">
              <div class="col-12">
                <label for="correo" class="form-label">Correo Electrónico</label>
                <input type="email" name="correo" id="correo" class="form-control" required>
              </div>
            </div>

            <!-- Quinto grupo: Ocupación -->
            <div class="row mb-3">
              <div class="col-12">
                <label for="ocupacion" class="form-label">Ocupación</label>
                <input type="text" name="ocupacion" id="ocupacion" class="form-control" required>
              </div>
            </div>
          </div>

          <div class="">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
          </div>
        </form>
      </div> <!-- Cierre modal-body -->

    </div> <!-- Cierre modal-content -->
  </div> <!-- Cierre modal-dialog -->
</div> <!-- Cierre modal -->