<?php
session_start(); // Asegúrate de iniciar la sesión

require 'C:/xampp/htdocs/IPSPUPTM/config/database.php';
include 'C:/xampp/htdocs/IPSPUPTM/app/configuracion/bitacora/bitacora.php';

header('Content-Type: application/json'); // Indica que la respuesta será en formato JSON

// Verifica si todos los datos necesarios fueron proporcionados
if (isset($_POST['cedula'], $_POST['nombre'], $_POST['apellido'],
          $_POST['fechanacimiento'], $_POST['genero'],
          $_POST['telefono'], $_POST['correo'], $_POST['ocupacion'])) {

    // Limpia los datos para prevenir inyección SQL
    $cedula = htmlspecialchars($_POST['cedula']);
    $nombre = htmlspecialchars($_POST['nombre']);
    $apellido = htmlspecialchars($_POST['apellido']);
    $fechanacimiento = htmlspecialchars($_POST['fechanacimiento']);
    $genero = htmlspecialchars($_POST['genero']);
    $telefono = htmlspecialchars($_POST['telefono']);
    $correo = htmlspecialchars($_POST['correo']);
    $ocupacion = htmlspecialchars($_POST['ocupacion']);

    try {
        // Inicia una transacción
        $conn->begin_transaction();

        // Actualizar datos en la tabla persona
        $sql_persona = "UPDATE persona
                            SET nombre = ?, apellido = ?, fechanacimiento = ?,
                            genero = ?, telefono = ?, correo = ?, ocupacion = ?
                            WHERE cedula = ?";

        $stmt_persona = $conn->prepare($sql_persona);

        if ($stmt_persona) {
            $stmt_persona->bind_param("ssssssss", $nombre, $apellido, $fechanacimiento, $genero, $telefono, $correo, $ocupacion, $cedula);

            if ($stmt_persona->execute()) {
                // Ahora actualiza la tabla afiliados (si fuera necesario)
                $sql_afiliados = "UPDATE afiliados
                                    SET updated_at = NOW()
                                    WHERE cedula = ?";

                $stmt_afiliados = $conn->prepare($sql_afiliados);

                if ($stmt_afiliados) {
                    $stmt_afiliados->bind_param("s", $cedula);

                    if ($stmt_afiliados->execute()) {
                        // Registrar en bitácora
                        $usuario = $_SESSION['username']; 
                        $accion = "Edición de Afiliado";
                        $descripcion = "Se han actualizado los datos del afiliado con cédula: $cedula, Nombre: $nombre, Apellido: $apellido";
                        registrarEnBitacora($conn, $usuario, $accion, $descripcion);

                        $conn->commit();
                        echo json_encode(['success' => true, 'message' => 'Afiliado actualizado correctamente.']);
                    } else {
                        $conn->rollback();
                        echo json_encode(['success' => false, 'message' => 'Error al actualizar afiliado: ' . $stmt_afiliados->error]);
                    }

                    $stmt_afiliados->close();
                } else {
                    $conn->rollback();
                    echo json_encode(['success' => false, 'message' => 'Error al preparar consulta para afiliados: ' . $conn->error]);
                }
            } else {
                $conn->rollback();
                echo json_encode(['success' => false, 'message' => 'Error al actualizar en personas: ' . $stmt_persona->error]);
            }

            $stmt_persona->close();
        } else {
            $conn->rollback();
            echo json_encode(['success' => false, 'message' => 'Error al preparar consulta para personas: ' . $conn->error]);
        }

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Ocurrió un error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error: Todos los campos obligatorios no fueron proporcionados.']);
}

$conn->close(); // Cierra la conexión
?>

