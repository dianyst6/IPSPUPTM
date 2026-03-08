<?php
require_once 'C:/xampp/htdocs/IPSPUPTM/config/database.php';
include 'C:/xampp/htdocs/IPSPUPTM/config/alertify.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];

    // Buscar al usuario por nombre de usuario
    $sql_usuario = "SELECT id FROM usuarios WHERE username = ?";
    $stmt_usuario = $conn->prepare($sql_usuario);

    if ($stmt_usuario) {
        $stmt_usuario->bind_param("s", $username);
        $stmt_usuario->execute();
        $result_usuario = $stmt_usuario->get_result();

        if ($result_usuario->num_rows == 1) {
            $row_usuario = $result_usuario->fetch_assoc();
            $user_id = $row_usuario['id'];

            // Obtener las preguntas de seguridad del usuario desde la tabla 'respuestas_seguridad'
            $sql_preguntas = "SELECT rs.pregunta_seguridad_id, ps.pregunta
                                 FROM respuestas_seguridad rs
                                 INNER JOIN preguntas_seguridad ps ON rs.pregunta_seguridad_id = ps.ID
                                 WHERE rs.usuario_id = ?";
            $stmt_preguntas = $conn->prepare($sql_preguntas);
            $stmt_preguntas->bind_param("i", $user_id);
            $stmt_preguntas->execute();
            $result_preguntas = $stmt_preguntas->get_result();

            if ($result_preguntas->num_rows > 0) {
                // Almacenar las preguntas en un array para mostrarlas en el formulario
                $preguntas_usuario = [];
                while ($row_pregunta = $result_preguntas->fetch_assoc()) {
                    $preguntas_usuario[$row_pregunta['pregunta_seguridad_id']] = $row_pregunta['pregunta'];
                }

                // Mostrar formulario con las preguntas de seguridad
                echo '<!DOCTYPE html>';
                echo '<html lang="es">';
                echo '<head>';
                echo '<meta charset="UTF-8">';
                echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
                echo '<title>Verificar Preguntas de Seguridad</title>';
                echo '<link rel="stylesheet" href="/IPSPUPTM/assets/css/bootstrap.min.css">';
                echo '<link rel="stylesheet" href="/IPSPUPTM/assets/css/inicio.css">';
                echo '</head>';
                echo '<body>';
                echo '<div class="container mt-5">';
                echo '<div class="row justify-content-center">';
                echo '<div class="col-md-6">';
                echo '<div class="card shadow p-4">';
                echo '<div class="logo-container">';  
                echo '<img src="/IPSPUPTM/recursos/img/IPSPUPTMlogo.png" alt="Logo IPSPUPTM" class="logo">';
                echo '</div>'; 
                echo '<h2 class="mb-3">Verificar Preguntas de Seguridad</h2>';
                echo '<p class="mb-3">Por favor, responde las siguientes preguntas de seguridad.</p>';
                echo '<form action="verificar_respuestas.php" method="POST">';
                echo '<input type="hidden" name="user_id" value="' . $user_id . '">';

                $i = 1;
                foreach ($preguntas_usuario as $pregunta_id => $pregunta_texto) {
                    echo '<div class="mb-3">';
                    echo '<label for="respuesta_seguridad_' . $i . '" class="form-label">' . htmlspecialchars($pregunta_texto) . '</label>';
                    echo '<input type="hidden" name="pregunta_id_' . $i . '" value="' . $pregunta_id . '">';
                    echo '<input type="text" class="form-control" id="respuesta_seguridad_' . $i . '" name="respuesta_seguridad[' . $pregunta_id . ']" required>';
                    echo '</div>';
                    $i++;
                }

                echo '<button type="submit" class="btn btn-primary w-100">Verificar Respuestas</button>';
                echo '</form>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '<script src= "/IPSPUPTM/assets/js/bootstrap.bundle.min.js"></script>';
                echo '</body>';
                echo '</html>';

            } else {
                echo '<div class="alert alert-warning" role="alert">Este usuario no tiene configuradas preguntas de seguridad. <a href="recuperar_contrasena_form.php" class="alert-link">Volver</a></div>';
            }
            $stmt_preguntas->close();

        } else {
            echo '<div class="alert alert-danger" role="alert">El nombre de usuario no existe. <a href="recuperar_contrasena_form.php" class="alert-link">Inténtalo de nuevo</a>.</div>';
        }
        $stmt_usuario->close();
    } else {
        echo '<div class="alert alert-danger" role="alert">Error al preparar la consulta para el usuario.</div>';
    }
}
?>
