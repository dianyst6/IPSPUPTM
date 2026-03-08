<?php
session_start(); 
require 'C:/xampp/htdocs/IPSPUPTM/config/database.php';
include 'C:/xampp/htdocs/IPSPUPTM/app/configuracion/bitacora/bitacora.php'; // Incluye la bitácora

header('Content-Type: application/json'); // Establece el tipo de contenido a JSON para las respuestas de la API

if (isset($_POST['id_cita'])) { // Verifica si se proporciona el ID de la cita
    $id_cita = intval($_POST['id_cita']); // Limpia el dato para mayor seguridad

    try {
        // Inicia una transacción
        $conn->begin_transaction();

        // Consulta para obtener información de la cita antes de eliminarla (para la bitácora)
        $sql_info_cita = "
            SELECT c.id_cita, c.fecha_cita, c.descripcion, e.nombre_especialidad,
                CASE 
                    WHEN ca.idcita IS NOT NULL THEN 'Afiliado'
                    WHEN cb.idcita IS NOT NULL THEN 'Beneficiario'
                    ELSE 'Desconocido'
                END AS tipo_paciente,
                CASE 
                    WHEN ca.idcita IS NOT NULL THEN CONCAT(p_a.nombre, ' ', p_a.apellido)
                    WHEN cb.idcita IS NOT NULL THEN CONCAT(p_b.nombre, ' ', p_b.apellido)
                    ELSE 'No especificado'
                END AS nombre_paciente
            FROM citas c
            LEFT JOIN citas_afil ca ON c.id_cita = ca.idcita
            LEFT JOIN afiliados a ON ca.id_afiliado = a.id
            LEFT JOIN persona p_a ON a.cedula = p_a.cedula
            LEFT JOIN citas_benef cb ON c.id_cita = cb.idcita
            LEFT JOIN beneficiarios b ON cb.id_beneficiario = b.id
            LEFT JOIN persona p_b ON b.cedula = p_b.cedula
            LEFT JOIN especialidades e ON c.id_especialidad = e.id_especialidad
            WHERE c.id_cita = ?
        ";
        $stmt_info = $conn->prepare($sql_info_cita);

        if ($stmt_info) {
            $stmt_info->bind_param("i", $id_cita);
            $stmt_info->execute();
            $result_info = $stmt_info->get_result();

            if ($result_info->num_rows > 0) {
                $info_cita = $result_info->fetch_assoc();
            } else {
                throw new Exception("La cita no existe o ya fue eliminada.");
            }

            $stmt_info->close();
        } else {
            throw new Exception("Error al preparar la consulta para obtener información de la cita: " . $conn->error);
        }

        // Eliminar relaciones en la tabla citas_afil
        $sql_citas_afil = "DELETE FROM citas_afil WHERE idcita = ?";
        $stmt_citas_afil = $conn->prepare($sql_citas_afil);

        if ($stmt_citas_afil) {
            $stmt_citas_afil->bind_param("i", $id_cita);

            if (!$stmt_citas_afil->execute()) {
                throw new Exception("Error al eliminar relaciones en citas_afil: " . $stmt_citas_afil->error);
            }

            $stmt_citas_afil->close();
        } else {
            throw new Exception("Error al preparar la consulta para eliminar relaciones en citas_afil: " . $conn->error);
        }

        // Eliminar relaciones en la tabla citas_benef
        $sql_citas_benef = "DELETE FROM citas_benef WHERE idcita = ?";
        $stmt_citas_benef = $conn->prepare($sql_citas_benef);

        if ($stmt_citas_benef) {
            $stmt_citas_benef->bind_param("i", $id_cita);

            if (!$stmt_citas_benef->execute()) {
                throw new Exception("Error al eliminar relaciones en citas_benef: " . $stmt_citas_benef->error);
            }

            $stmt_citas_benef->close();
        } else {
            throw new Exception("Error al preparar la consulta para eliminar relaciones en citas_benef: " . $conn->error);
        }

        // Eliminar la cita principal
        $sql_citas = "DELETE FROM citas WHERE id_cita = ?";
        $stmt_citas = $conn->prepare($sql_citas);

        if ($stmt_citas) {
            $stmt_citas->bind_param("i", $id_cita);

            if (!$stmt_citas->execute()) {
                throw new Exception("Error al eliminar la cita: " . $stmt_citas->error);
            }

            $stmt_citas->close();
        } else {
            throw new Exception("Error al preparar la consulta para eliminar la cita: " . $conn->error);
        }

        // Registrar en la bitácora
        $usuario = $_SESSION['username']; 
        $accion = "Eliminación de Cita";
        $descripcion = "Se eliminó la cita: " . $info_cita['descripcion'] . 
                        " (ID Cita: $id_cita), Paciente: " . $info_cita['nombre_paciente'] . 
                        " (" . $info_cita['tipo_paciente'] . "), Fecha: " . $info_cita['fecha_cita'] . 
                        ", Especialidad: " . $info_cita['nombre_especialidad'];
        registrarenBitacora($conn, $usuario, $accion, $descripcion);

        // Confirma la transacción
        $conn->commit();

        // Envía una respuesta JSON de éxito
        echo json_encode(['success' => true, 'message' => 'Cita eliminada correctamente.']);
        exit();
    } catch (Exception $e) {
        // Cancela la transacción si algo falla
        $conn->rollback();
        // Envía una respuesta JSON de error
        echo json_encode(['success' => false, 'message' => 'Ocurrió un error: ' . $e->getMessage()]);
        exit();
    }
} else {
    // Envía una respuesta JSON de error si no se proporciona el ID de la cita
    echo json_encode(['success' => false, 'message' => 'ID de cita no proporcionado.']);
    exit();
}

$conn->close();
?>
