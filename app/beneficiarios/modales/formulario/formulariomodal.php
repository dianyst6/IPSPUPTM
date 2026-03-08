<div class="modal fade" id="formulariomodal" tabindex="-1" aria-labelledby="formulariomodallabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="FormularioModalLabel">Formulario de registro</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/IPSPUPTM/app/beneficiarios/modales/formulario/guardar.php" method="post">
                    <div class="row">
                        <div class="mb-3 col-12">
                            <label for="cedula_afil" class="form-label">Afiliado relacionado</label>
                            <select name="cedula_afil" id="cedula_afil" class="form-select" required>
                                <option value="" selected disabled>Seleccionar afiliado...</option>
                                <?php
                                // Consulta para cargar afiliados existentes
                                $sql_afiliados = "
                                SELECT a.id AS cedula_afil, CONCAT(p.nombre, ' ', p.apellido) AS nombre_completo
                                FROM afiliados a
                                JOIN persona p ON a.cedula = p.cedula
                                ORDER BY p.nombre ASC";
                                $result_afiliados = $conn->query($sql_afiliados);

                                if ($result_afiliados) {
                                    if ($result_afiliados->num_rows > 0) {
                                        while ($row_afiliado = $result_afiliados->fetch_assoc()) {
                                            echo '<option value="' . $row_afiliado['cedula_afil'] . '">' . $row_afiliado['nombre_completo'] . '</option>';
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
                            <select name="genero" id="genero" class="form-select" required>
                                <option value=""> Seleccionar... </option>
                                <option value="masculino">Masculino</option>
                                <option value="femenino">Femenino</option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
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
                        <div class="col-12">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>