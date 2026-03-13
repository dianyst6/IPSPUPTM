<link href="/IPSPUPTM/assets/select2/css/select2.min.css" rel="stylesheet" />

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
                       <option value=""></option> 
                      <?php
                     $sql_afiliados = "
                        SELECT a.id AS id_afil, a.cedula, CONCAT(p.nombre, ' ', p.apellido) AS nombre_completo
                        FROM afiliados a
                        JOIN persona p ON a.cedula = p.cedula
                                            ORDER BY p.nombre ASC";
                            $result_afiliados = $conn->query($sql_afiliados);

                            if ($result_afiliados && $result_afiliados->num_rows > 0) {
                                while ($row_afiliado = $result_afiliados->fetch_assoc()) {
                                    // Formato exacto para la búsqueda: Cédula | Nombre
                                    echo '<option value="' . $row_afiliado['id_afil'] . '">' 
                                        . $row_afiliado['cedula'] . ' | ' . $row_afiliado['nombre_completo'] . 
                                        '</option>';
                                }
                            }
                            ?>
                        </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="cedula" class="form-label">Cédula</label>
                            <input type="text" name="cedula" id="cedula" class="form-control" required>
                            <div id="cedulaFeedback" class="text-danger mt-1" style="display:none; font-size: 0.875em;"></div>
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
                            <button type="submit" id="btnRegistrar" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<<<<<<< correcciones-gabi
 <script>
document.addEventListener('DOMContentLoaded', function() {
    
    // === CARGA DINÁMICA DE SELECT2 ===
    var script = document.createElement('script');
    script.src = '/IPSPUPTM/assets/select2/js/select2.min.js';
    script.onload = function() {
        // Se activa cuando el modal de registro se termina de mostrar
        $('#formulariomodal').on('shown.bs.modal', function () {
            $('#cedula_afil').select2({
                dropdownParent: $('#formulariomodal'), // Esto permite que se vea y se pueda escribir
                placeholder: "Busque por cédula o nombre...",
                width: '100%',
                allowClear: true
            });
        });
    };
    document.head.appendChild(script);
});
</script>

=======
<script>
// Usar delegación de eventos para asegurar que funcione incluso si el modal se carga dinámicamente
document.body.addEventListener('input', function(event) {
    if (event.target && event.target.id === 'cedula') {
        let cedulaInput = event.target;
        let cedula = cedulaInput.value.trim();
        const feedback = document.getElementById('cedulaFeedback');
        const btnRegistrar = document.getElementById('btnRegistrar');

        if (cedula.length > 0) {
            fetch('/IPSPUPTM/app/beneficiarios/modales/formulario/check_cedula.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'cedula=' + encodeURIComponent(cedula)
            })
            .then(response => response.json())
            .then(data => {
                if (data.existe_beneficiario) {
                    feedback.textContent = 'La cédula ya está registrada como beneficiario.';
                    feedback.style.display = 'block';
                    btnRegistrar.disabled = true;
                    cedulaInput.classList.add('is-invalid');
                } else if (data.existe_afiliado) {
                    feedback.textContent = 'La cédula ya está registrada como afiliado.';
                    feedback.style.display = 'block';
                    btnRegistrar.disabled = true;
                    cedulaInput.classList.add('is-invalid');
                } else {
                    feedback.style.display = 'none';
                    btnRegistrar.disabled = false;
                    cedulaInput.classList.remove('is-invalid');
                }
            })
            .catch(error => {
                console.error('Error verificando la cédula:', error);
            });
        } else {
            feedback.style.display = 'none';
            btnRegistrar.disabled = false;
            cedulaInput.classList.remove('is-invalid');
        }
    }
});
</script>
>>>>>>> main
