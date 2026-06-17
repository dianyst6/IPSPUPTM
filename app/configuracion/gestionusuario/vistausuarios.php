<?php
include '../IPSPUPTM/config/database.php';
include '../IPSPUPTM/config/alertify.php';

$rowsPerPage = 15;
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($currentPage - 1) * $rowsPerPage;

// 1. Ocultar administrador (ID 7) en la consulta
$sqlUsuarios = "
    SELECT id, username
    FROM usuarios
    WHERE id != 7
    LIMIT $offset, $rowsPerPage
";
$Usuarios = $conn->query($sqlUsuarios);

// 1. Ocultar administrador en el conteo total
$totalRowsResult = $conn->query("SELECT COUNT(*) AS total FROM usuarios WHERE id != 7");
$totalRows = $totalRowsResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $rowsPerPage);
?>
<div class="card shadow-lg">
    <div class="container-fluid mt-4 px-4">
         <h1 class="fw-bold text-center" style="color: #062974;">Gestión de Usuarios</h1>
        <p>Aquí puedes agregar, editar, eliminar y configurar los permisos de cada usuario.</p>
        
        <div class="table-responsive">
            <table class="table table-sm table-striped table-hover mt-4">
                <thead class="table-dark">
                    <tr>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Pregunta 1</th>
                        <th>Respuesta 1</th>
                        <th>Pregunta 2</th>
                        <th>Respuesta 2</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = $Usuarios->fetch_assoc()) {
                        $userId = $row['id'];
                        $sql_respuestas = "SELECT ps.pregunta, rs.respuesta FROM respuestas_seguridad rs 
                                           JOIN preguntas_seguridad ps ON rs.pregunta_seguridad_id = ps.ID 
                                           WHERE rs.usuario_id = $userId ORDER BY rs.pregunta_seguridad_id ASC LIMIT 2";
                        $result_respuestas = $conn->query($sql_respuestas);
                        $respuestas = $result_respuestas->fetch_all(MYSQLI_ASSOC);

                        $sql_rol = "SELECT r.Nombre FROM usuarios u JOIN roles r ON u.role_id = r.id WHERE u.id = $userId";
                        $rol_nombre = $conn->query($sql_rol)->fetch_assoc()['Nombre'] ?? 'N/A';
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($row['username']); ?></td>
                            <td><?= htmlspecialchars($rol_nombre); ?></td>
                            <td><?= $respuestas[0]['pregunta'] ?? 'N/A'; ?></td>
                            <td><?= $respuestas[0]['respuesta'] ?? 'N/A'; ?></td>
                            <td><?= $respuestas[1]['pregunta'] ?? 'N/A'; ?></td>
                            <td><?= $respuestas[1]['respuesta'] ?? 'N/A'; ?></td>
                            <td class="text-center">
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#eliminamodal" data-bs-id="<?= $row['id']; ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <nav>
            <ul class="pagination justify-content-center">
                <?php for ($page = 1; $page <= $totalPages; $page++): ?>
                    <li class="page-item <?= ($page == $currentPage) ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $page ?>"><?= $page ?></a></li>
                <?php endfor; ?>
            </ul>
        </nav>

        <?php
        $result_preguntas = $conn->query("SELECT ID, pregunta FROM preguntas_seguridad");
        $preguntas = [];
        while ($row = $result_preguntas->fetch_assoc()) {
            $preguntas[$row['ID']] = $row['pregunta'];
        }
        ?>

        <div class="mt-4">
            <h2 class="mb-3">Agregar Usuario</h2>
            <div class="card shadow p-4 mb-5">
                <form action="/IPSPUPTM/Inicio/registro.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Nombre de usuario</label>
                        <input type="text" class="form-control" id="username" name="username" required autocomplete="off">
                        <div id="usernameFeedback" class="form-text"></div>
                    </div> 
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div><div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                            <i class="fas fa-eye" id="confirmEyeIcon"></i>
                        </button>
                    </div>
                    <div id="passwordFeedback" class="form-text"></div>
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
                                <label for="ci_medico" class="form-label">Cédula</label>
                                <input type="text" name="ci_medico" id="ci_medico" class="form-control" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="telefono_personal" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefono_personal" name="telefono_personal">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="pregunta_seguridad_id1" class="form-label">Pregunta 1</label>
                        <select class="form-select" id="pregunta_seguridad_id1" name="pregunta_seguridad_id1" required>
                            <option value="">Selecciona...</option>
                            <?php foreach ($preguntas as $id => $p): ?><option value="<?= $id ?>"><?= $p ?></option><?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="respuesta_seguridad1" class="form-label">Respuesta 1</label>
                        <input type="text" class="form-control" id="respuesta_seguridad1" name="respuesta_seguridad1" required>
                    </div>

                    <div class="mb-3">
                        <label for="pregunta_seguridad_id2" class="form-label">Pregunta 2</label>
                        <select class="form-select" id="pregunta_seguridad_id2" name="pregunta_seguridad_id2" required>
                            <option value="">Selecciona...</option>
                            <?php foreach ($preguntas as $id => $p): ?><option value="<?= $id ?>"><?= $p ?></option><?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="respuesta_seguridad2" class="form-label">Respuesta 2</label>
                        <input type="text" class="form-control" id="respuesta_seguridad2" name="respuesta_seguridad2" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Registrar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
   document.addEventListener('DOMContentLoaded', function() {

    // --- 1. Elementos del DOM ---
    const form = document.querySelector('form');
    const submitBtn = form.querySelector('button[type="submit"]');
    const allRequiredInputs = form.querySelectorAll('input[required], select[required]');
    
    const pass1 = document.getElementById('password');
    const pass2 = document.getElementById('confirm_password');
    const passFeedback = document.getElementById('passwordFeedback');
    
    const usernameInput = document.getElementById("username");
    const usernameFeedback = document.getElementById("usernameFeedback");

    // --- 2. Función Maestra de Validación ---
    function checkFormValidity() {
        let isFormValid = true;

        // A. Validar campos vacíos (obligatorios)
        allRequiredInputs.forEach(input => {
            if (input.value.trim() === '') {
                isFormValid = false;
            }
        });

        // B. Validar contraseñas
        if (pass2.value !== '') { // Solo validamos si hay algo escrito
            if (pass1.value !== pass2.value) {
                passFeedback.textContent = '❌ Las contraseñas no coinciden';
                passFeedback.className = 'form-text text-danger';
                pass2.classList.add('is-invalid');
                isFormValid = false;
            } else {
                passFeedback.textContent = '✔️ Las contraseñas coinciden';
                passFeedback.className = 'form-text text-success';
                pass2.classList.remove('is-invalid');
                pass2.classList.add('is-valid');
            }
        } else {
            passFeedback.textContent = '';
            pass2.classList.remove('is-invalid', 'is-valid');
        }

        // C. Validar disponibilidad de usuario (check de la clase CSS)
        if (usernameInput.classList.contains('is-invalid')) {
            isFormValid = false;
        }

        // D. Aplicar estado al botón
        submitBtn.disabled = !isFormValid;
    }

    // --- 3. Inicialización de Eventos ---
    
    // Escuchar cambios en todos los campos obligatorios
    allRequiredInputs.forEach(input => {
        input.addEventListener('input', checkFormValidity);
        input.addEventListener('change', checkFormValidity);
    });

    // Lógica específica para el check del usuario (debido al fetch)
    usernameInput.addEventListener("input", function () {
        const username = usernameInput.value.trim();
        if (username.length < 3) return; // Esperar a que escriba algo

        const formData = new FormData();
        formData.append("username", username);

        fetch("/IPSPUPTM/app/configuracion/gestionusuario/verificar_usuario.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(data => {
            if (data.trim() === "existe") {
                usernameFeedback.textContent = "❌ Usuario no disponible.";
                usernameFeedback.className = "form-text text-danger";
                usernameInput.classList.add("is-invalid");
            } else {
                usernameFeedback.textContent = "✔️ Usuario disponible.";
                usernameFeedback.className = "form-text text-success";
                usernameInput.classList.remove("is-invalid");
            }
            checkFormValidity(); // Re-validar todo el formulario tras el fetch
        });
    });

    // --- 4. Funcionalidades Extras ---
    
    // Toggle Contraseñas
    function toggleVisibility(buttonId, inputId, iconId) {
        const toggleBtn = document.getElementById(buttonId);
        const inputField = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (!toggleBtn) return;
        toggleBtn.addEventListener('click', () => {
            const type = inputField.getAttribute('type') === 'password' ? 'text' : 'password';
            inputField.setAttribute('type', type);
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    }
    toggleVisibility('togglePassword', 'password', 'eyeIcon');
    toggleVisibility('toggleConfirmPassword', 'confirm_password', 'confirmEyeIcon');

    // Deshabilitar preguntas duplicadas
    const q1 = document.getElementById('pregunta_seguridad_id1');
    const q2 = document.getElementById('pregunta_seguridad_id2');
    function actualizarOpciones() {
        for (let option of q2.options) option.disabled = (option.value === q1.value && q1.value !== "");
        for (let option of q1.options) option.disabled = (option.value === q2.value && q2.value !== "");
        checkFormValidity(); // Validar formulario al cambiar select
    }
    q1.addEventListener('change', actualizarOpciones);
    q2.addEventListener('change', actualizarOpciones);

    // Lógica Médico
    const roleSelector = document.getElementById('role_id');
    const camposMedico = document.getElementById('campos-medico');
    roleSelector.addEventListener('change', function() {
        const inputsMedico = camposMedico.querySelectorAll('input, select');
        if (this.value === '3') {
            camposMedico.style.display = 'block';
            inputsMedico.forEach(i => i.setAttribute('required', 'required'));
        } else {
            camposMedico.style.display = 'none';
            inputsMedico.forEach(i => i.removeAttribute('required'));
        }
        checkFormValidity(); // Re-validar porque cambiaron los campos obligatorios
    });

    // --- Estado Inicial ---
    submitBtn.disabled = true; // Empieza deshabilitado
}); 
</script>


