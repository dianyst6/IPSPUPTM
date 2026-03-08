<?php

require_once 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ... (Tu código para obtener los datos POST y la validación de campos vacíos) ...
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role_id = $_POST['role_id'];
    $pregunta_seguridad_id1 = $_POST['pregunta_seguridad_id1'];
    $respuesta_seguridad1 = $_POST['respuesta_seguridad1'];
    $pregunta_seguridad_id2 = $_POST['pregunta_seguridad_id2'];
    $respuesta_seguridad2 = $_POST['respuesta_seguridad2'];

    // Validación de datos
    $response = [];
    if (empty($username) || empty($password) || empty($role_id) || empty($pregunta_seguridad_id1) || empty($respuesta_seguridad1) || empty($pregunta_seguridad_id2) || empty($respuesta_seguridad2)) {
        $response['success'] = false;
        $response['message'] = 'Por favor completa todos los campos, incluyendo las preguntas de seguridad';
        echo json_encode($response);
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
            // El usuario ya existe, notificar y salir
            $response['success'] = false;
            $response['message'] = 'El nombre de usuario ya está en uso. Por favor, elige otro.';
            echo "<script>";
            echo "alert('El nombre de usuario ya está en uso ');";
            echo "window.history.back();";
            echo "</script>";
          
            echo json_encode($response);
            $stmt_check->close();
            exit();
        }
        $stmt_check->close();
    }
    // FIN de la verificación

    // Encriptar la contraseña
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insertar en la tabla de usuarios
    $sql_usuario = "INSERT INTO usuarios (username, password, role_id) VALUES (?, ?, ?)";
    $stmt_usuario = $conn->prepare($sql_usuario);

    if ($stmt_usuario) {
        $stmt_usuario->bind_param("ssi", $username, $hashed_password, $role_id);
        
        // La ejecución ahora debería ser segura porque ya comprobamos la unicidad.
        if ($stmt_usuario->execute()) { 
            $user_id = $conn->insert_id;

            // ... (Tu código para insertar las respuestas de seguridad) ...
            $errores_seguridad = false;
            
            // Respuesta 1
            $sql_respuesta1 = "INSERT INTO respuestas_seguridad (usuario_id, pregunta_seguridad_id, respuesta) VALUES (?, ?, ?)";
            $stmt_respuesta1 = $conn->prepare($sql_respuesta1);
            if ($stmt_respuesta1) {
                $stmt_respuesta1->bind_param("iis", $user_id, $pregunta_seguridad_id1, $respuesta_seguridad1);
                if (!$stmt_respuesta1->execute()) {
                    $errores_seguridad = true;
                }
                $stmt_respuesta1->close();
            } else {
                $errores_seguridad = true; // Error al preparar la consulta de seguridad 1
            }


            // Respuesta 2
            $sql_respuesta2 = "INSERT INTO respuestas_seguridad (usuario_id, pregunta_seguridad_id, respuesta) VALUES (?, ?, ?)";
            $stmt_respuesta2 = $conn->prepare($sql_respuesta2);
            if ($stmt_respuesta2) {
                $stmt_respuesta2->bind_param("iis", $user_id, $pregunta_seguridad_id2, $respuesta_seguridad2);
                if (!$stmt_respuesta2->execute()) {
                    $errores_seguridad = true;
                }
                $stmt_respuesta2->close();
            } else {
                $errores_seguridad = true; // Error al preparar la consulta de seguridad 2
            }


            if (!$errores_seguridad) {
                header('Location: /IPSPUPTM/home.php?vista=usuarios');
                exit(); 
            } else {
                // Opcional: Si falla la inserción de respuestas de seguridad, 
                // deberías considerar ELIMINAR el usuario que acabas de crear 
                // para evitar registros incompletos (transacción).
                $response['success'] = false;
                $response['message'] = 'Usuario creado, pero hubo un error al registrar las respuestas de seguridad.';
                echo json_encode($response);
                exit();
            }

        } else {
            // Este else ahora capturará otros errores de ejecución, no el de duplicidad.
            $response['success'] = false;
            $response['message'] = 'Error al registrar el usuario: ' . $stmt_usuario->error; // Muestra el error real
            echo json_encode($response);
            exit();
        }
        $stmt_usuario->close();
    } else {
        $response['success'] = false;
        $response['message'] = 'Error al preparar la consulta de usuario: ' . $conn->error;
        echo json_encode($response);
        exit();
    }
}

$conn->close();
?>