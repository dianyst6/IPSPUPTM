<div class="modal fade" id="eliminamodal" tabindex="-1" aria-labelledby="eliminamodallabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="eliminamodallabel">Aviso</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ¿Desea eliminar la cita?
      </div>
      <div class="modal-footer"> 
        <form action="/IPSPUPTM/app/citas/modales/eliminar/eliminar.php" method="post"> 
          <input type="hidden" name="id_cita" id="id_cita" class="form-control" required>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-danger">Eliminar</button>
        </form>
      </div>      
    </div>
  </div>
</div>

