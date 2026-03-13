<div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="editmodallabel" aria-hidden="true">
  <div class="modal-dialog"> <div class="modal-content">
      
      <div class="modal-header">
                <h1 class="modal-title fs-5" id="FormularioModalLabel">Editar persona de Comunidad UPTM</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

      <div class="modal-body">
        <form action="/IPSPUPTM/app/comunidaduptm/actualizar/actualizar.php" method="post">
          
          <div class="container">
            <div class="mb-3">
              <label for="cedula" class="form-label">Cédula</label>
              <input type="text" id="cedula" name="cedula" class="form-control bg-light">
            </div>

            <div class="mb-3">
              <label for="nombre" class="form-label">Nombre</label>
              <input type="text" id="nombre" name="nombre" class="form-control bg-light" >
            </div>

            <div class="mb-3">
              <label for="apellido" class="form-label">Apellido</label>
              <input type="text" id="apellido" name="apellido" class="form-control bg-light">
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