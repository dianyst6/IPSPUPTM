<?php
session_start(); 
require 'C:/xampp/htdocs/IPSPUPTM/config/database.php';
include 'C:/xampp/htdocs/IPSPUPTM/app/configuracion/bitacora/bitacora.php'; 

header('Content-Type: application/json'); 

if (isset($_POST['id_cita'])) { 
    $id_cita = intval($_POST['id_cita']); 

    try {
        $conn->begin_transaction();

        // Consulta para obtener información de la cita antes de cancelarla
        $sql_info_cita = "
            SELECT c.id_cita, c.fecha_cita, c.descripcion, e.nombre_especialidad,
                CASE 
                    WHEN ca.idcita IS NOT NULL THEN 'Afiliado'
                    WHEN cb.idcita IS NOT NULL THEN 'Beneficiario'
                    WHEN cu.idcita IS NOT NULL THEN 'Comunidad UPTM'
                    ELSE 'Desconocido'
                END AS tipo_paciente,
                CASE 
                    WHEN ca.idcita IS NOT NULL THEN CONCAT(p_a.nombre, ' ', p_a.apellido)
                    WHEN cb.idcita IS NOT NULL THEN CONCAT(p_b.nombre, ' ', p_b.apellido)
                    WHEN cu.idcita IS NOT NULL THEN CONCAT(com.nombre, ' ', com.apellido)
                    ELSE 'No especificado'
                END AS nombre_paciente
            FROM citas c
            LEFT JOIN citas_afil ca ON c.id_cita = ca.idcita
            LEFT JOIN afiliados a ON ca.id_afiliado = a.id
            LEFT JOIN persona p_a ON a.cedula = p_a.cedula
            LEFT JOIN citas_benef cb ON c.id_cita = cb.idcita
            LEFT JOIN beneficiarios b ON cb.id_beneficiario = b.id
            LEFT JOIN persona p_b ON b.cedula = p_b.cedula
            LEFT JOIN citas_uptm cu ON c.id_cita = cu.idcita
            LEFT JOIN comunidad_uptm com ON cu.id_externo = com.id
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
                throw new Exception("La cita no existe.");
            }
            $stmt_info->close();
        }

        // ACTUALIZACIÓN: Cambiar el estado a 'cancelada' en lugar de eliminar
        $sql_citas = "UPDATE citas SET estado = 'cancelada' WHERE id_cita = ?";
        $stmt_citas = $conn->prepare($sql_citas);

        if ($stmt_citas) {
            $stmt_citas->bind_param("i", $id_cita);
            if (!$stmt_citas->execute()) {
                throw new Exception("Error al cancelar la cita: " . $stmt_citas->error);
            }
            $stmt_citas->close();
        }

        // Registrar en la bitácora
        $usuario = $_SESSION['username'] ?? 'Sistema'; 
        $accion = "Cancelación de Cita";
        $descripcion = "Se canceló la cita: " . $info_cita['descripcion'] . 
                        " (ID Cita: $id_cita), Paciente: " . $info_cita['nombre_paciente'] . 
                        " (" . $info_cita['tipo_paciente'] . "), Fecha: " . $info_cita['fecha_cita'] . 
                        ", Especialidad: " . $info_cita['nombre_especialidad'];
        registrarenBitacora($conn, $usuario, $accion, $descripcion);

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Cita cancelada correctamente.']);
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Ocurrió un error: ' . $e->getMessage()]);
        exit();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID de cita no proporcionado.']);
    exit();
}
$conn->close();
?>
