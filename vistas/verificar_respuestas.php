<?php
session_start();
require_once 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: recuperar_contrasena.php");
    exit();
}

$user_id = $_POST['user_id'];
$respuestas_usuario = $_POST['respuesta_seguridad'];

// 1. Obtener respuestas correctas de la DB
$sql_respuestas = "SELECT pregunta_seguridad_id, respuesta FROM respuestas_seguridad WHERE usuario_id = ?";
$stmt_respuestas = $conn->prepare($sql_respuestas);
$stmt_respuestas->bind_param("i", $user_id);
$stmt_respuestas->execute();
$result_respuestas = $stmt_respuestas->get_result();

$respuestas_correctas = [];
while ($row = $result_respuestas->fetch_assoc()) {
    $respuestas_correctas[$row['pregunta_seguridad_id']] = $row['respuesta'];
}

// 2. Verificar
$respuestas_coinciden = true;
foreach ($respuestas_usuario as $pregunta_id => $valor) {
    if (!isset($respuestas_correctas[$pregunta_id]) || $respuestas_correctas[$pregunta_id] !== $valor) {
        $respuestas_coinciden = false;
        break;
    }
}

// 3. Resultado
if ($respuestas_coinciden) {
    // ÉXITO
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Restablecer Contraseña</title>
        <link rel="stylesheet" href="/IPSPUPTM/assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="/IPSPUPTM/assets/css/inicio.css">
    </head>
    <body>
        <?php include __DIR__ . '/formulario_reset.php'; ?>
    </body>
    </html>
    <?php
} else {
    // ERROR: Guardamos el mensaje en sesión para que el Script lo tome
    $_SESSION['mensaje_alertify'] = "Las respuestas no coinciden. Inténtalo de nuevo.";
    $_SESSION['tipo_alertify'] = "error";

    // Recargar preguntas
    $sql_preg = "SELECT rs.pregunta_seguridad_id, ps.pregunta FROM respuestas_seguridad rs 
                 INNER JOIN preguntas_seguridad ps ON rs.pregunta_seguridad_id = ps.ID 
                 WHERE rs.usuario_id = ?";
    $stmt_preg = $conn->prepare($sql_preg);
    $stmt_preg->bind_param("i", $user_id);
    $stmt_preg->execute();
    $result_preg = $stmt_preg->get_result();

    $preguntas_usuario = [];
    while ($row = $result_preg->fetch_assoc()) {
        $preguntas_usuario[$row['pregunta_seguridad_id']] = $row['pregunta'];
    }
    
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Verificar Preguntas</title>
        <link rel="stylesheet" href="/IPSPUPTM/assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="/IPSPUPTM/assets/css/inicio.css">
        
        <?php include __DIR__ . '/../config/alertify.php'; ?>
    </head>
    <body>
        <?php include __DIR__ . '/formulario_seguridad.php'; ?>

        <?php if (isset($_SESSION['mensaje_alertify'])): ?>
            <script>
                window.onload = function() {
                    alertify.<?php echo $_SESSION['tipo_alertify']; ?>("<?php echo $_SESSION['mensaje_alertify']; ?>");
                };
            </script>
            <?php 
                // Limpiamos las variables para que no salga el mensaje al recargar la página
                unset($_SESSION['mensaje_alertify']); 
                unset($_SESSION['tipo_alertify']); 
            ?>
        <?php endif; ?>
    </body>
    </html>
    <?php
}
?>