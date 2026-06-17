<?php
require_once 'C:/xampp/htdocs/IPSPUPTM/config/database.php';
include 'C:/xampp/htdocs/IPSPUPTM/config/alertify.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $respuestas_usuario = $_POST['respuesta_seguridad'];
    $respuestas_correctas = [];

    // 1. Obtener las respuestas correctas de la DB
    $sql_respuestas = "SELECT pregunta_seguridad_id, respuesta FROM respuestas_seguridad WHERE usuario_id = ?";
    $stmt_respuestas = $conn->prepare($sql_respuestas);
    $stmt_respuestas->bind_param("i", $user_id);
    $stmt_respuestas->execute();
    $result_respuestas = $stmt_respuestas->get_result();

    while ($row_respuesta = $result_respuestas->fetch_assoc()) {
        $respuestas_correctas[$row_respuesta['pregunta_seguridad_id']] = $row_respuesta['respuesta'];
    }

    // 2. Verificar si coinciden
    $respuestas_coinciden = true;
    foreach ($respuestas_usuario as $pregunta_id => $respuesta_usuario) {
        if (!isset($respuestas_correctas[$pregunta_id]) || $respuestas_correctas[$pregunta_id] !== $respuesta_usuario) {
            $respuestas_coinciden = false;
            break;
        }
    }

    // --- AQUÍ ESTÁ LA LÓGICA: Si coinciden, cambias pass. Si no, recargas el form ---
    
    if ($respuestas_coinciden) {
        // ÉXITO: Mostrar formulario nueva contraseña
        echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><link rel="stylesheet" href="/IPSPUPTM/assets/css/bootstrap.min.css"><link rel="stylesheet" href="/IPSPUPTM/assets/css/inicio.css"></head><body>';
        echo '<div class="container mt-5"><div class="row justify-content-center"><div class="col-md-6"><div class="card shadow p-4">';
        echo '<h2>Restablecer Contraseña</h2><form action="/IPSPUPTM/vistas/actualizar_contrasena.php" method="POST">';
        echo '<input type="hidden" name="user_id" value="' . $user_id . '">';
        echo '<div class="mb-3"><label>Nueva Contraseña</label><input type="password" class="form-control" name="nueva_password" required></div>';
        echo '<div class="mb-3"><label>Confirmar Nueva Contraseña</label><input type="password" class="form-control" name="confirmar_password" required></div>';
        echo '<button type="submit" class="btn btn-primary w-100">Restablecer</button></form></div></div></div></div></body></html>';
    } else {
        // ERROR: Lanzas alerta y vuelves a pintar el formulario
        echo '<script>window.onload = function() { alertify.error("Las respuestas no coinciden. Inténtalo de nuevo."); };</script>';
        
        // Volvemos a obtener las preguntas para pintar el formulario
        $sql_preguntas = "SELECT rs.pregunta_seguridad_id, ps.pregunta FROM respuestas_seguridad rs 
                          INNER JOIN preguntas_seguridad ps ON rs.pregunta_seguridad_id = ps.ID 
                          WHERE rs.usuario_id = ?";
        $stmt_preg = $conn->prepare($sql_preguntas);
        $stmt_preg->bind_param("i", $user_id);
        $stmt_preg->execute();
        $result_preg = $stmt_preg->get_result();

        echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><link rel="stylesheet" href="/IPSPUPTM/assets/css/bootstrap.min.css"><link rel="stylesheet" href="/IPSPUPTM/assets/css/inicio.css"></head><body>';
        echo '<div class="container mt-5"><div class="row justify-content-center"><div class="col-md-6"><div class="card shadow p-4">';
        echo '<h2>Verificar Preguntas</h2><form action="verificar_respuestas.php" method="POST">';
        echo '<input type="hidden" name="user_id" value="' . $user_id . '">';
        
        while ($row = $result_preg->fetch_assoc()) {
            echo '<div class="mb-3"><label class="form-label">' . htmlspecialchars($row['pregunta']) . '</label>';
            echo '<input type="text" class="form-control" name="respuesta_seguridad[' . $row['pregunta_seguridad_id'] . ']" required></div>';
        }
        
        echo '<button type="submit" class="btn btn-primary w-100">Verificar Respuestas</button></form></div></div></div></div></body></html>';
    }

} else {
    header("Location: recuperar_contrasena_form.php");
    exit();
}
?>