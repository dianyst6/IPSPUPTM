

<div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="editmodallabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editmodalLabel">Editar beneficiario </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="/IPSPUPTM/app/beneficiarios/modales/actualizar/actualizar.php" method="post">
            <input type="hidden" name="id" id="id">
            <div class="row">
            <div class="mb-3 col-12">
                        <label for="cedula_afil" class="form-label">Afiliado relacionado</label>
                        <select name="cedula_afil" id="cedula_afil" class="form-select" required>
                            <option value="" selected disabled>Seleccionar afiliado...</option>
                            <?php
                            // Consulta para cargar afiliados existentes
                            $sql_afiliados = "
                            SELECT a.id AS id_afiliado, CONCAT(p.nombre, ' ', p.apellido) AS nombre_completo
                            FROM afiliados a
                            JOIN persona p ON a.cedula = p.cedula
                            ORDER BY p.nombre ASC";
                            $result_afiliados = $conn->query($sql_afiliados);

                            if ($result_afiliados) {
                                if ($result_afiliados->num_rows > 0) {
                                    while ($row_afiliado = $result_afiliados->fetch_assoc()) {
                                        $selected = '';
                                        // Comparamos el ID del afiliado (value de la opción) con el cedula_afil del beneficiario
                                        if ($afiliado_seleccionado_id !== null && $row_afiliado['id_afiliado'] == $afiliado_seleccionado_id) {
                                            $selected = 'selected';
                                        }
                                        echo '<option value="' . $row_afiliado['id_afiliado'] . '" ' . $selected . '>' . $row_afiliado['nombre_completo'] . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No hay afiliados disponibles</option>';
                                }
                            } else {
                                echo '<option value="">Error al cargar afiliados</option>';
                            }
                            ?>
                        </select>
                    </div>
            <div class="mb-3 col-md-6"> 
                <label for="cedula" class="form-label">Cédula</label>
                <input type="text" name="cedula" id="cedula" class="form-control" required>
            </div>
            <div class="mb-3 col-md-6"> 
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required>
            </div>
            <div class="mb-3 col-md-6"> 
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" name="apellido" id="apellido" class="form-control" required>
            </div>
            <div class="mb-3 col-md-6"> 
                <label for="fechanacimiento" class="form-label">Fecha de Nacimiento</label>
                <input type="date" name="fechanacimiento" id="fechanacimiento" class="form-control" required>
            </div>
            <div class="mb-3 col-md-6">
                <label for="genero" class="form-label">Género</label>
                <input type="text" id="genero" class="form-control" readonly name= "genero">
            </div>
            <div class="mb-3  col-md-6"> 
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" name="telefono" id="telefono" class="form-control" required>
            </div>
            <div class="mb-3 col-md-6">   
                <label for="correo" class="form-label">Correo Electrónico</label>  
                <input type="email" name="correo" id="correo" class="form-control" required>    
            </div>
            <div class="mb-3 col-md-6"> 
                <label for="ocupacion" class="form-label">Ocupación</label>
                <input type="text" name="ocupacion" id="ocupacion" class="form-control" required>
            </div>
            <div class=""> 
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
        </div>
      </div>
    </div>
  </div>
</div>
