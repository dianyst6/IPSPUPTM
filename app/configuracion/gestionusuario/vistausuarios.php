<?php
include '../IPSPUPTM/config/database.php';

$rowsPerPage = 15;
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($currentPage - 1) * $rowsPerPage;

// Obtener usuarios con paginación
$sqlUsuarios = "
    SELECT id, username
    FROM usuarios
    LIMIT $offset, $rowsPerPage
";
$Usuarios = $conn->query($sqlUsuarios);

$totalRowsResult = $conn->query("SELECT COUNT(*) AS total FROM usuarios");
$totalRows = $totalRowsResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $rowsPerPage);
?>
<div class="card shadow-lg">
    <div class="container mt-4 m-3">
        <h1 class="fw-bold text-center" style="color: #062974;">Gestión de Usuarios</h1>
        <p>Aquí puedes agregar, editar, eliminar y
            configurar los permisos de cada usuario.</p>
        <table class="table table-sm table-striped table-hover mt-4">
            <thead class="table-dark text-center">
                <tr>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Pregunta de Seguridad 1</th>
                    <th>Respuesta 1</th>
                    <th>Pregunta de Seguridad 2</th>
                    <th>Respuesta 2</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $Usuarios->fetch_assoc()) {
                    $userId = $row['id'];

                    $sql_respuestas = "SELECT
                                        ps.pregunta AS pregunta,
                                        rs.respuesta AS respuesta,
                                        rs.pregunta_seguridad_id AS pregunta_id
                                    FROM respuestas_seguridad rs
                                    JOIN preguntas_seguridad ps ON rs.pregunta_seguridad_id = ps.ID
                                    WHERE rs.usuario_id = $userId
                                    ORDER BY rs.pregunta_seguridad_id ASC
                                    LIMIT 2"; // Limitamos a las primeras dos respuestas
                    $result_respuestas = $conn->query($sql_respuestas);
                    $respuestas = array();
                    while ($row_respuesta = $result_respuestas->fetch_assoc()) {
                        $respuestas[] = array(
                            'pregunta' => htmlspecialchars($row_respuesta['pregunta']),
                            'respuesta' => htmlspecialchars($row_respuesta['respuesta'])
                        );
                    }

                    $sql_rol = "SELECT r.Nombre AS role_name
                                    FROM usuarios u
                                    JOIN roles r ON u.role_id = r.id
                                    WHERE u.id = $userId";
                    $result_rol = $conn->query($sql_rol);
                    $rol_row = $result_rol->fetch_assoc();
                    $rol_nombre = $rol_row ? $rol_row['role_name'] : 'N/A';
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($rol_nombre); ?></td>
                        <td><?php echo isset($respuestas[0]['pregunta']) ? $respuestas[0]['pregunta'] : 'N/A'; ?></td>
                        <td><?php echo isset($respuestas[0]['respuesta']) ? $respuestas[0]['respuesta'] : 'N/A'; ?></td>
                        <td><?php echo isset($respuestas[1]['pregunta']) ? $respuestas[1]['pregunta'] : 'N/A'; ?></td>
                        <td><?php echo isset($respuestas[1]['respuesta']) ? $respuestas[1]['respuesta'] : 'N/A'; ?></td>
                        <td class="text-center">
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#eliminamodal"
                                data-bs-id="<?= $row['id']; ?>">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <nav>
            <ul class="pagination justify-content-center">
                <?php for ($page = 1; $page <= $totalPages; $page++): ?>
                    <li class="page-item <?= ($page == $currentPage) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $page ?>"><?= $page ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>

        <?php
        $sql_preguntas = "SELECT ID, pregunta FROM preguntas_seguridad";
        $result_preguntas = $conn->query($sql_preguntas);
        $preguntas = [];
        if ($result_preguntas->num_rows > 0) {
            while ($row = $result_preguntas->fetch_assoc()) {
                $preguntas[$row['ID']] = $row['pregunta'];
            }
        }
        ?>

        <div class="mt-4">
            <h2 class="mb-3">Agregar Usuario</h2>
            <div class="card shadow p-4">
                <form action="/IPSPUPTM/Inicio/registro.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Nombre de usuario</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="role_id" class="form-label">Rol</label>
                        <select class="form-select" id="role_id" name="role_id" required>
                            <option value="1">Admin</option>
                            <option value="2">Usuario</option>
                            <option value="3">Médico</option>
                        </select>
                    </div>

                    <div id="campos-medico" style="display: none;" class="border p-3 mb-3 bg-light rounded">
                        <h5 class="text-primary">Datos del Médico</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="ci_medico" class="form-label">Cédula del Médico</label>
                                <input type="text"
                                    name="ci_medico"
                                    id="ci_medico"
                                    class="form-control"
                                    inputmode="numeric"
                                    pattern="[0-9]*"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="telefono_personal" class="form-label">Teléfono Personal</label>
                                <input type="text" class="form-control" id="telefono_personal" name="telefono_personal">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="id_especialidad" class="form-label">Especialidad</label>
                                <select name="especialidad" id="especialidad" class="form-select" required>
                                    <option value="" selected disabled>Seleccionar...</option>
                                    <?php
                                    $sql_especialidades = "SELECT id_especialidad, nombre_especialidad FROM especialidades ORDER BY nombre_especialidad ASC";
                                    $result_espe = $conn->query($sql_especialidades);
                                    while ($row_e = $result_espe->fetch_assoc()) {
                                        echo '<option value="' . $row_e['id_especialidad'] . '">' . $row_e['nombre_especialidad'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="pregunta_seguridad_id1" class="form-label">Pregunta de seguridad 1</label>
                        <select class="form-select" id="pregunta_seguridad_id1" name="pregunta_seguridad_id1" required>
                            <option value="">Selecciona una pregunta</option>
                            <?php foreach ($preguntas as $id => $pregunta): ?>
                                <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($pregunta); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="respuesta_seguridad1" class="form-label">Respuesta de seguridad 1</label>
                        <input type="text" class="form-control" id="respuesta_seguridad1" name="respuesta_seguridad1"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="pregunta_seguridad_id2" class="form-label">Pregunta de seguridad 2</label>
                        <select class="form-select" id="pregunta_seguridad_id2" name="pregunta_seguridad_id2" required>
                            <option value="">Selecciona una pregunta</option>
                            <?php foreach ($preguntas as $id => $pregunta): ?>
                                <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($pregunta); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="respuesta_seguridad2" class="form-label">Respuesta de seguridad 2</label>
                        <input type="text" class="form-control" id="respuesta_seguridad2" name="respuesta_seguridad2"
                            required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Registrar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include 'C:/xampp/htdocs/IPSPUPTM/app/configuracion/gestionusuario/eliminar/eliminarmodal.php'; ?>
<script src="/IPSPUPTM/assets/js/usuarios.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelector = document.getElementById('role_id');
        const camposMedico = document.getElementById('campos-medico');

        // Seleccionamos los inputs internos para controlar el "required"
        const inputsMedico = camposMedico.querySelectorAll('input');

        roleSelector.addEventListener('change', function() {
            if (this.value === '3') { // "3" es el ID de Médico
                camposMedico.style.display = 'block';

                // Hacer que los campos sean obligatorios solo si es médico
                inputsMedico.forEach(input => input.setAttribute('required', 'required'));
            } else {
                camposMedico.style.display = 'none';

                // Quitar obligatoriedad y limpiar valores
                inputsMedico.forEach(input => {
                    input.removeAttribute('required');
                    input.value = '';
                });
            }
        });
    });
</script>