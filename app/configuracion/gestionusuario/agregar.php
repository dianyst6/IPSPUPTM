<!-- Modal Agregar Usuario -->  
<div class="modal fade" id="modalAgregarUsuario" tabindex="-1" aria-labelledby="modalAgregarUsuarioLabel" aria-hidden="true">  
  <div class="modal-dialog">  
    <div class="modal-content">  
      <form action="/IPSPUPTM/Inicio/registro.php" method="POST">  
        <div class="modal-header">  
          <h5 class="modal-title" id="modalAgregarUsuarioLabel">Registrar Nuevo Usuario</h5>  
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>  
        </div>  
        <div class="modal-body">  
          <div class="mb-3">  
            <label for="usernameModal" class="form-label">Nombre de usuario</label>  
            <input type="text" class="form-control" id="usernameModal" name="username" required>  
          </div>  
          <div class="mb-3">  
            <label for="passwordModal" class="form-label">Contraseña</label>  
            <input type="password" class="form-control" id="passwordModal" name="password" required>  
          </div>  
          <div class="mb-3">  
            <label for="role_idModal" class="form-label">Rol</label>  
            <select class="form-select" id="role_idModal" name="role_id" required>  
              <option value="1">Admin</option>  
              <option value="2">Usuario</option>  
            </select>  
          </div>  
        </div>  
        <div class="modal-footer">  
          <button type="submit" class="btn btn-success">Registrar</button>  
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>  
        </div>  
      </form>  
    </div>  
  </div>  
</div>  