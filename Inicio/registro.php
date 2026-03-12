<?php

require_once 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role_id = $_POST['role_id'];
    $pregunta_seguridad_id1 = $_POST['pregunta_seguridad_id1'];
    $respuesta_seguridad1 = $_POST['respuesta_seguridad1'];
    $pregunta_seguridad_id2 = $_POST['pregunta_seguridad_id2'];
    $respuesta_seguridad2 = $_POST['respuesta_seguridad2'];

    // Validación de datos básicos
    if (empty($username) || empty($password) || empty($role_id) || empty($pregunta_seguridad_id1) || empty($respuesta_seguridad1) || empty($pregunta_seguridad_id2) || empty($respuesta_seguridad2)) {
        echo "<script>alert('Por favor completa todos los campos requeridos'); window.history.back();</script>";
        exit();
    }

    // 1. VERIFICAR SI EL NOMBRE DE USUARIO YA EXISTE
    $sql_check = "SELECT id FROM usuarios WHERE username = ?";
    $stmt_check = $conn->prepare($sql_check);
    if ($stmt_check) {
        $stmt_check->bind_param("s", $username);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            echo "<script>alert('El nombre de usuario ya está en uso. Por favor, elige otro.'); window.history.back();</script>";
            $stmt_check->close();
            exit();
        }
        $stmt_check->close();
    }

    // Encriptar la contraseña
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Iniciar Transacción para asegurar que se guarde TODO o NADA
    $conn->begin_transaction();

    try {
        // --- PASO A: INSERTAR USUARIO ---
        $sql_usuario = "INSERT INTO usuarios (username, password, role_id) VALUES (?, ?, ?)";
        $stmt_usuario = $conn->prepare($sql_usuario);
        $stmt_usuario->bind_param("ssi", $username, $hashed_password, $role_id);
        $stmt_usuario->execute();
        
        $user_id = $conn->insert_id;

        // --- PASO B: INSERTAR RESPUESTAS DE SEGURIDAD ---
        $sql_resp = "INSERT INTO respuestas_seguridad (usuario_id, pregunta_seguridad_id, respuesta) VALUES (?, ?, ?)";
        $stmt_resp = $conn->prepare($sql_resp);
        
        // Respuesta 1
        $stmt_resp->bind_param("iis", $user_id, $pregunta_seguridad_id1, $respuesta_seguridad1);
        $stmt_resp->execute();

        // Respuesta 2
        $stmt_resp->bind_param("iis", $user_id, $pregunta_seguridad_id2, $respuesta_seguridad2);
        $stmt_resp->execute();

        // --- PASO C: SI ES MÉDICO (ID 3), INSERTAR EN TABLA MEDICOS ---
        if ($role_id == '3') {
            $ci_medico = $_POST['ci_medico'];
            $especialidad = $_POST['especialidad'];
            $telefono = $_POST['telefono_personal'];

            // Validación extra para médicos
            if (empty($ci_medico) || empty($especialidad)) {
                throw new Exception("Los campos de médico son obligatorios.");
            }

            $sql_medico = "INSERT INTO medicos (ci_medico, id_usuario, especialidad, telefono_personal) VALUES (?, ?, ?, ?)";
            $stmt_medico = $conn->prepare($sql_medico);
            $stmt_medico->bind_param("siss", $ci_medico, $user_id, $especialidad, $telefono);
            $stmt_medico->execute();
        }

        // SI LLEGAMOS AQUÍ, TODO ESTÁ BIEN
        $conn->commit();
        header('Location: /IPSPUPTM/home.php?vista=usuarios');
        exit();

    } catch (Exception $e) {
        // SI ALGO FALLA, DESHACER TODO
        $conn->rollback();
        echo "<script>alert('Error al registrar el usuario: " . $e->getMessage() . "'); window.history.back();</script>";
    }
}

$conn->close();
?>