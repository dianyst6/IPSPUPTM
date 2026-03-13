<?php
session_start();

require 'C:/xampp/htdocs/IPSPUPTM/config/database.php';
include 'C:/xampp/htdocs/IPSPUPTM/app/configuracion/bitacora/bitacora.php'; // Incluye la bitácora

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
        // Inicia una transacción para asegurar consistencia
        $conn->begin_transaction();

        // Consulta para obtener los datos actuales antes de la actualización (para la bitácora)
        $sql_actual = "SELECT nombre, apellido, fechanacimiento, genero, telefono, correo, ocupacion 
                        FROM persona WHERE cedula = ?";
        $stmt_actual = $conn->prepare($sql_actual);

        if ($stmt_actual) {
            $stmt_actual->bind_param("s", $cedula);
            $stmt_actual->execute();
            $result_actual = $stmt_actual->get_result();

            if ($result_actual->num_rows > 0) {
                $datos_antes = $result_actual->fetch_assoc(); // Guarda los datos previos para el registro en bitácora
            }
            else {
                throw new Exception("La cédula no se encuentra en los registros.");
            }

            $stmt_actual->close();
        }
        else {
            throw new Exception("Error al preparar consulta para obtener datos actuales: " . $conn->error);
        }

        // Actualizar datos en la tabla persona
        $sql_persona = "UPDATE persona
                            SET nombre = ?, apellido = ?, fechanacimiento = ?, 
                                genero = ?, telefono = ?, correo = ?, ocupacion = ? 
                            WHERE cedula = ?";
        $stmt_persona = $conn->prepare($sql_persona);

        if ($stmt_persona) {
            $stmt_persona->bind_param("ssssssss", $nombre, $apellido, $fechanacimiento, $genero, $telefono, $correo, $ocupacion, $cedula);

            if (!$stmt_persona->execute()) {
                throw new Exception("Error al actualizar en persona: " . $stmt_persona->error);
            }

            $stmt_persona->close();
        }
        else {
            throw new Exception("Error al preparar consulta para persona: " . $conn->error);
        }

        // Actualizar datos en la tabla beneficiarios (solo el campo updated_at)
        $sql_beneficiarios = "UPDATE beneficiarios 
                                    SET updated_at = NOW() 
                                    WHERE cedula = ?";
        $stmt_beneficiarios = $conn->prepare($sql_beneficiarios);

        if ($stmt_beneficiarios) {
            $stmt_beneficiarios->bind_param("s", $cedula);

            if (!$stmt_beneficiarios->execute()) {
                throw new Exception("Error al actualizar en beneficiarios: " . $stmt_beneficiarios->error);
            }

            $stmt_beneficiarios->close();
        }
        else {
            throw new Exception("Error al preparar consulta para beneficiarios: " . $conn->error);
        }

        // Registrar en la bitácora los cambios realizados
        $usuario = $_SESSION['username'];
        $accion = "Actualización de Beneficiario";
        $descripcion = "Se actualizó al beneficiario con cédula $cedula y nombre $nombre $apellido";
        registrarenBitacora($conn, $usuario, $accion, $descripcion);

        // Confirma los cambios
        $conn->commit();

        // Envía una respuesta JSON de éxito
        echo json_encode(['success' => true, 'message' => 'Beneficiario actualizado correctamente']);
        exit();

    }
    catch (Exception $e) {
        // Cancela la transacción si algo falla
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Ocurrió un error: ' . $e->getMessage()]);
        exit();
    }
}
else {
    echo json_encode(['success' => false, 'message' => 'Error: Todos los campos obligatorios no fueron proporcionados.']);
    exit();
}

$conn->close(); // Cierra la conexión
?>
