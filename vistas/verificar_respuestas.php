<?php
session_start();
require_once 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

$respuestas_coinciden = false;
$user_id = null;
$error_password = null;

// 1. MODIFICAMOS EL CONTROL DE ACCESO:
// Permitimos entrar por GET *únicamente* si viene rebotado de un error al cambiar la contraseña
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    if (isset($_GET['error']) && isset($_GET['user_id'])) {
        $user_id = intval($_GET['user_id']);
        $respuestas_coinciden = true; // Forzamos a que muestre el formulario de cambio directo
        $error_password = $_GET['error'];
    } else {
        header("Location: recuperar_contrasena.php");
        exit();
    }
} else {
    // Flujo normal cuando viene de responder las preguntas por POST
    $user_id = $_POST['user_id'];
    $respuestas_usuario = $_POST['respuesta_seguridad'];

    // Obtener respuestas correctas de la DB
    $sql_respuestas = "SELECT pregunta_seguridad_id, respuesta FROM respuestas_seguridad WHERE usuario_id = ?";
    $stmt_respuestas = $conn->prepare($sql_respuestas);
    $stmt_respuestas->bind_param("i", $user_id);
    $stmt_respuestas->execute();
    $result_respuestas = $stmt_respuestas->get_result();

    $respuestas_correctas = [];
    while ($row = $result_respuestas->fetch_assoc()) {
        $respuestas_correctas[$row['pregunta_seguridad_id']] = $row['respuesta'];
    }

    // Verificar respuestas
    $respuestas_coinciden = true;
    foreach ($respuestas_usuario as $pregunta_id => $valor) {
        if (!isset($respuestas_correctas[$pregunta_id]) || $respuestas_correctas[$pregunta_id] !== $valor) {
            $respuestas_coinciden = false;
            break;
        }
    }
}

if ($respuestas_coinciden) {
    // === CASO ÉXITO (O RETORNO POR ERROR DE CONTRASEÑA) ===
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Restablecer Contraseña</title>
        <link rel="stylesheet" href="/IPSPUPTM/assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="/IPSPUPTM/assets/css/inicio.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        
        <?php include __DIR__ . '/../config/alertify.php'; ?>
    </head>
    <body>
        
        <div class="min-vh-100 d-flex align-items-center justify-content-center">
            <?php include __DIR__ . '/formulario_reset.php'; ?>
        </div>

        <script src="/IPSPUPTM/assets/js/bootstrap.bundle.min.js"></script>
        <script>
            // Función nativa para activar los ojitos de este formulario
            function agragarAlternarOjo(buttonId, inputId, iconId) {
                const btn = document.getElementById(buttonId);
                const input = document.getElementById(inputId);
                const icon = document.getElementById(iconId);
                
                if (btn && input && icon) {
                    btn.addEventListener('click', function() {
                        if (input.type === 'password') {
                            input.type = 'text';
                            icon.classList.remove('fa-eye');
                            icon.classList.add('fa-eye-slash');
                        } else {
                            input.type = 'password';
                            icon.classList.remove('fa-eye-slash');
                            icon.classList.add('fa-eye');
                        }
                    });
                }
            }

            window.onload = function() {
                // 1. Inicializamos los eventos de los ojitos
                agragarAlternarOjo('toggleResetPassword', 'reset_password', 'resetEyeIcon');
                agragarAlternarOjo('toggleResetConfirmPassword', 'reset_confirm_password', 'resetConfirmEyeIcon');

                // 2. Manejo de alertas de error por URL si las hay
                <?php if ($error_password !== null): ?>
                    if ("<?php echo $error_password; ?>" === "no_coinciden") {
                        alertify.error("❌ Las contraseñas introducidas no coinciden.");
                    } else if ("<?php echo $error_password; ?>" === "vacia") {
                        alertify.error("❌ La nueva contraseña no puede estar vacía.");
                    } else {
                        alertify.error("❌ Ocurrió un error al procesar la solicitud.");
                    }
                    
                    // Limpiamos el error pero dejamos el user_id activo
                    window.history.replaceState({}, document.title, window.location.pathname + "?user_id=<?php echo $user_id; ?>");
                <?php endif; ?>
            };
        </script>
    </body>
    </html>
    <?php
} else {
    // === CASO ERROR EN RESPUESTAS DE SEGURIDAD ===
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
        
        <div class="min-vh-100 d-flex align-items-center justify-content-center">
            <?php include __DIR__ . '/formulario_seguridad.php'; ?>
        </div>

        <script src="/IPSPUPTM/assets/js/bootstrap.bundle.min.js"></script>
        <?php if (isset($_SESSION['mensaje_alertify'])): ?>
            <script>
                window.onload = function() {
                    alertify.<?php echo $_SESSION['tipo_alertify']; ?>("<?php echo $_SESSION['mensaje_alertify']; ?>");
                };
            </script>
            <?php 
                unset($_SESSION['mensaje_alertify']); 
                unset($_SESSION['tipo_alertify']); 
            ?>
        <?php endif; ?>
    </body>
    </html>
    <?php
}
?>