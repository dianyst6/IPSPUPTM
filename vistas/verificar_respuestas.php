<?php
require_once 'C:/xampp/htdocs/IPSPUPTM/config/database.php';
include 'C:/xampp/htdocs/IPSPUPTM/config/alertify.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $respuestas_usuario = $_POST['respuesta_seguridad'];
    $respuestas_correctas = [];

    // Obtener las respuestas correctas de la base de datos
    $sql_respuestas = "SELECT pregunta_seguridad_id, respuesta
                       FROM respuestas_seguridad
                       WHERE usuario_id = ?";
    $stmt_respuestas = $conn->prepare($sql_respuestas);
    $stmt_respuestas->bind_param("i", $user_id);
    $stmt_respuestas->execute();
    $result_respuestas = $stmt_respuestas->get_result();

    if ($result_respuestas->num_rows > 0) {
        while ($row_respuesta = $result_respuestas->fetch_assoc()) {
            $respuestas_correctas[$row_respuesta['pregunta_seguridad_id']] = $row_respuesta['respuesta'];
        }

        $respuestas_coinciden = true;
        foreach ($respuestas_usuario as $pregunta_id => $respuesta_usuario) {
            if (!isset($respuestas_correctas[$pregunta_id]) || $respuestas_correctas[$pregunta_id] !== $respuesta_usuario) {
                $respuestas_coinciden = false;
                break;
            }
        }

        if ($respuestas_coinciden) {
            // Las respuestas coinciden, mostrar formulario para nueva contraseña
            echo '<!DOCTYPE html>';
            echo '<html lang="es">';
            echo '<head>';
            echo '<meta charset="UTF-8">';
            echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
            echo '<title>Restablecer Contraseña</title>';
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
            echo '<h2 class="mb-3">Restablecer Contraseña</h2>';
            echo '<p class="mb-3">Ingresa tu nueva contraseña.</p>';
            echo '<form action="/IPSPUPTM/vistas/actualizar_contrasena.php" method="POST">';
            echo '<input type="hidden" name="user_id" value="' . $user_id . '">';
            echo '<div class="mb-3">';
            echo '<label for="nueva_password" class="form-label">Nueva Contraseña</label>';
            echo '<input type="password" class="form-control" id="nueva_password" name="nueva_password" required>';
            echo '</div>';
            echo '<div class="mb-3">';
            echo '<label for="confirmar_password" class="form-label">Confirmar Nueva Contraseña</label>';
            echo '<input type="password" class="form-control" id="confirmar_password" name="confirmar_password" required>';
            echo '</div>';
            echo '<button type="submit" class="btn btn-primary w-100">Restablecer Contraseña</button>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '<script src= "/IPSPUPTM/assets/js/bootstrap.bundle.min.js"></script>';
            echo '</body>';
            echo '</html>';
        } else {
            echo '<div class="alert alert-danger" role="alert">Las respuestas de seguridad no coinciden. <a href="verificar_usuario.php" class="alert-link">Inténtalo de nuevo</a>.</div>';
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">Error: No se recibieron las respuestas.</div>';
    }
} else {
    // Si se accede a este script directamente sin POST
    header("Location: recuperar_contrasena_form.php");
    exit();
}
?>