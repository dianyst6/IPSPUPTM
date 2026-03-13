<div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="editmodallabel" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-scrollable">    <div class="modal-content">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="FormularioModalLabel">Editar Cita</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        <div class="modal-body" style="max-height: 70vh;">
                <form action="/IPSPUPTM/app/citas/modales/actualizar/actualizar.php" method="post" id="formularioEditarCita">
                    <input type="hidden" name="id_cita" id="id_cita_editar">
                    
                    <div class="card mb-3 border-light shadow-sm">
                        <div class="card-body">
                            
                            <div id="campos_internos_editar">
                                <label for="id_paciente_editar" class="form-label fw-bold">Paciente (Interno)</label>
                                <select name="id_paciente" id="id_paciente_editar" class="form-select">
                                    <option value="" disabled selected>Seleccione un paciente...</option>
                                    <?php
                                    // Cargar Afiliados
                                    $sql_afil = "SELECT a.id, CONCAT(p.nombre, ' ', p.apellido, ' - (Afiliado)') AS nombre_completo 
                                                 FROM afiliados a JOIN persona p ON a.cedula = p.cedula ORDER BY nombre_completo ASC";
                                    $res_afil = $conn->query($sql_afil);
                                    if($res_afil) while ($row = $res_afil->fetch_assoc()) {
                                        echo '<option value="' . $row['id'] . '">' . $row['nombre_completo'] . '</option>';
                                    }

                                    // Cargar Beneficiarios
                                    $sql_ben = "SELECT b.id, CONCAT(p.nombre, ' ', p.apellido, ' - (Beneficiario)') AS nombre_completo 
                                                FROM beneficiarios b JOIN persona p ON b.cedula = p.cedula ORDER BY nombre_completo ASC";
                                    $res_ben = $conn->query($sql_ben);
                                    if($res_ben) while ($row = $res_ben->fetch_assoc()) {
                                        echo '<option value="' . $row['id'] . '">' . $row['nombre_completo'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                        <div id="campos_externos_editar" style="display: none;">
                            <label class="form-label fw-bold text-primary">Datos del Paciente Externo</label>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <input type="text" name="nombre_ext" id="nombre_ext_editar" class="form-control bg-light" placeholder="Nombre" readonly>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="apellido_ext" id="apellido_ext_editar" class="form-control bg-light" placeholder="Apellido" readonly>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="cedula_ext" id="cedula_ext_editar" class="form-control bg-light" placeholder="Cédula" readonly>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id_especialidad_editar" class="form-label fw-bold">Especialidad</label>
                            <select name="id_especialidad" id="id_especialidad_editar" class="form-select" required>
                                <option value="" disabled selected>Seleccionar...</option>
                                <?php
                                $sql_esp = "SELECT id_especialidad, nombre_especialidad FROM especialidades ORDER BY nombre_especialidad ASC";
                                $res_esp = $conn->query($sql_esp);
                                if($res_esp) while ($row = $res_esp->fetch_assoc()) {
                                    echo '<option value="' . $row['id_especialidad'] . '">' . $row['nombre_especialidad'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="fecha_cita_editar" class="form-label fw-bold">Fecha y Hora</label>
                            <input type="datetime-local" name="fecha_cita" id="fecha_cita_editar" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion_editar" class="form-label fw-bold">Descripción / Motivo</label>
                        <textarea name="descripcion" id="descripcion_editar" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>