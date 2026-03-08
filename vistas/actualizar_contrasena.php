<?php
require_once 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $nueva_password = $_POST['nueva_password'];
    $confirmar_password = $_POST['confirmar_password'];

    // Validar que las contraseñas coincidan
    if ($nueva_password !== $confirmar_password) {
        echo "<script>
            alertify.error('Las contraseñas no coinciden.');
            window.location.href = 'javascript:history.back()';
          </script>";
        exit();
    }

    // Validar que la nueva contraseña no esté vacía
    if (empty($nueva_password)) {
        echo "<script>
            alertify.error('La nueva contraseña no puede estar vacía.');
            window.location.href = 'javascript:history.back()';
          </script>";
        exit();
    }

    // Encriptar la nueva contraseña
    $hashed_nueva_password = password_hash($nueva_password, PASSWORD_BCRYPT);

    // Actualizar la contraseña en la base de datos
    $sql_actualizar = "UPDATE usuarios SET password = ? WHERE id = ?";
    $stmt_actualizar = $conn->prepare($sql_actualizar);

    if ($stmt_actualizar) {
        $stmt_actualizar->bind_param("si", $hashed_nueva_password, $user_id);
        if ($stmt_actualizar->execute()) {
            // Redirigir a login.php con un parámetro en la URL
            header("Location: /IPSPUPTM/index.php?contrasena_restablecida=1");
            exit();
        } else {
            echo "<script>
              alertify.error('Error al actualizar la contraseña.');
              window.location.href = 'javascript:history.back()';
            </script>";
        }
        $stmt_actualizar->close();
    } else {
        echo "<script>
            alertify.error('Error al preparar la consulta de actualización.');
            window.location.href = 'javascript:history.back()';
          </script>";
    }
} else {
    // Si se accede a este script directamente sin POST
    header("Location: recuperar_contrasena_form.php");
    exit();
}
?>
