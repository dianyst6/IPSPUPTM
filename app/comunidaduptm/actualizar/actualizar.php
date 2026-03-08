<?php
session_start(); 

require 'C:/xampp/htdocs/IPSPUPTM/config/database.php';
include 'C:/xampp/htdocs/IPSPUPTM/app/configuracion/bitacora/bitacora.php';

header('Content-Type: application/json'); 

// 1. Verificamos solo los 3 campos que manejas ahora
if (isset($_POST['cedula'], $_POST['nombre'], $_POST['apellido'])) {

    $cedula   = htmlspecialchars($_POST['cedula']);
    $nombre   = htmlspecialchars($_POST['nombre']);
    $apellido = htmlspecialchars($_POST['apellido']);

    try {
        $conn->begin_transaction();

        // 2. Consulta simplificada a la tabla comunidad_uptm
        // Nota: Si los campos son readonly, esto técnicamente reescribirá lo mismo que ya hay
        $sql = "UPDATE comunidad_uptm SET nombre = ?, apellido = ? WHERE cedula = ?";

        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sss", $nombre, $apellido, $cedula);

            if ($stmt->execute()) {
                // 3. Registrar en bitácora
                $usuario = $_SESSION['username'] ?? 'Secretaria'; 
                $accion = "Edición de Comunidad UPTM";
                $descripcion = "Se actualizaron los datos del externo C.I: $cedula ($nombre $apellido)";
                
                registrarEnBitacora($conn, $usuario, $accion, $descripcion);

                $conn->commit();
                echo json_encode(['success' => true, 'message' => 'Datos actualizados correctamente.']);
            } else {
                throw new Exception("Error al ejecutar la actualización: " . $stmt->error);
            }
            $stmt->close();
        } else {
            throw new Exception("Error al preparar la consulta: " . $conn->error);
        }

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios (Cédula, Nombre o Apellido).']);
}

$conn->close();
?>