<?php
session_start(); 
require 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

header('Content-Type: application/json');

// 1. Verificación flexible: Siempre necesitamos id_cita, especialidad, fecha y desc.
// El paciente puede ser id_paciente (interno) O nombre_ext (externo).
if (isset($_POST['id_cita'], $_POST['id_especialidad'], $_POST['fecha_cita'], $_POST['descripcion'])) {

    $id_cita = intval($_POST['id_cita']);
    $id_especialidad = intval($_POST['id_especialidad']);
    $fecha_cita = $_POST['fecha_cita'];
    $descripcion = htmlspecialchars($_POST['descripcion']);
    
    // Capturamos las dos posibilidades
    $id_paciente = isset($_POST['id_paciente']) ? intval($_POST['id_paciente']) : null;
    $nombre_ext = isset($_POST['nombre_ext']) ? htmlspecialchars($_POST['nombre_ext']) : null;

    try {
        $conn->begin_transaction();

        // --- A. ACTUALIZAR TABLA PADRE (CITAS) ---
        $sql_citas = "UPDATE citas SET id_especialidad = ?, fecha_cita = ?, descripcion = ?, updated_at = NOW() WHERE id_cita = ?";
        $stmt_citas = $conn->prepare($sql_citas);
        $stmt_citas->bind_param("issi", $id_especialidad, $fecha_cita, $descripcion, $id_cita);
        
        if (!$stmt_citas->execute()) {
            throw new Exception("Error al actualizar la cita principal.");
        }
        $stmt_citas->close();

        // --- B. LÓGICA SEGÚN TIPO DE PACIENTE ---
        
        if (!empty($id_paciente)) {
            // CASO INTERNO: Buscamos si es Afiliado o Beneficiario
            $tipo_paciente = "";
            
            // Intentamos en Afiliados
            $check_af = $conn->prepare("SELECT id FROM afiliados WHERE id = ?");
            $check_af->bind_param("i", $id_paciente);
            $check_af->execute();
            
            if ($check_af->get_result()->num_rows > 0) {
                $sql_rel = "UPDATE citas_afil SET id_afiliado = ?, updated_at = NOW() WHERE idcita = ?";
                $tipo_paciente = "Afiliado";
            } else {
                // Si no, probamos en Beneficiarios
                $sql_rel = "UPDATE citas_benef SET id_beneficiario = ?, updated_at = NOW() WHERE idcita = ?";
                $tipo_paciente = "Beneficiario";
            }
            $check_af->close();

            $stmt_rel = $conn->prepare($sql_rel);
            $stmt_rel->bind_param("ii", $id_paciente, $id_cita);
            $stmt_rel->execute();
            $stmt_rel->close();

        } else if (!empty($nombre_ext)) {
            // CASO EXTERNO: Actualizar tabla comunidad_uptm
            $apellido_ext = $_POST['apellido_ext'];
            $cedula_ext = $_POST['cedula_ext'];

            // Buscamos el ID en la tabla comunidad a través de la intermedia
            $sql_get_u = "SELECT id_externo FROM citas_uptm WHERE idcita = ?";
            $st_get = $conn->prepare($sql_get_u);
            $st_get->bind_param("i", $id_cita);
            $st_get->execute();
            $res_u = $st_get->get_result();

            if ($row_u = $res_u->fetch_assoc()) {
                $id_comunidad = $row_u['id_externo'];
                $sql_upd_u = "UPDATE comunidad_uptm SET nombre = ?, apellido = ?, cedula = ? WHERE id = ?";
                $st_upd = $conn->prepare($sql_upd_u);
                $st_upd->bind_param("sssi", $nombre_ext, $apellido_ext, $cedula_ext, $id_comunidad);
                $st_upd->execute();
                $st_upd->close();
            }
            $st_get->close();
        }

        // --- C. BITÁCORA ---
        $usuario = $_SESSION['username'] ?? 'Sistema'; 
        $accion = "Actualización de Cita";
        $desc_bit = "Se modificó la cita ID: $id_cita";
        include_once 'C:/xampp/htdocs/IPSPUPTM/app/configuracion/bitacora/bitacora.php';
        if (function_exists('registrarenBitacora')) {
            registrarenBitacora($conn, $usuario, $accion, $desc_bit);
        }

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Cita actualizada correctamente']);

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Campos obligatorios faltantes']);
}

$conn->close();