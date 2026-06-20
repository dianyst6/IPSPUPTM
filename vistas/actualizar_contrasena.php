<?php
require_once 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $nueva_password = $_POST['nueva_password'];
    $confirmar_password = $_POST['confirmar_password'];

    // Validar que las contraseñas coincidan
    if ($nueva_password !== $confirmar_password) {
        header("Location: /IPSPUPTM/vistas/verificar_respuestas.php?error=no_coinciden&user_id=" . urlencode($user_id));
        exit();
    }

    // Validar que la nueva contraseña no esté vacía
    if (empty($nueva_password)) {
        header("Location: /IPSPUPTM/vistas/verificar_respuestas.php?error=vacia&user_id=" . urlencode($user_id));
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
            // Éxito: Redirigir al login principal
            header("Location: /IPSPUPTM/index.php?contrasena_restablecida=1");
            exit();
        } else {
            header("Location: /IPSPUPTM/vistas/verificar_respuestas.php?error=db_error&user_id=" . urlencode($user_id));
            exit();
        }
        $stmt_actualizar->close();
    } else {
        header("Location: /IPSPUPTM/vistas/verificar_respuestas.php?error=prepare_error&user_id=" . urlencode($user_id));
        exit();
    }
} else {
    header("Location: recuperar_contrasena_form.php");
    exit();
}
$conn->close();
?>