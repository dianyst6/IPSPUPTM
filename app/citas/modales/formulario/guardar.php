<?php  
require 'C:/xampp/htdocs/IPSPUPTM/config/database.php';  
include 'C:/xampp/htdocs/IPSPUPTM/app/configuracion/bitacora/bitacora.php';  
session_start(); 

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {  
    if (isset($_POST['id_especialidad'], $_POST['fecha_cita'], $_POST['descripcion'], $_POST['tipo_paciente'])) {  
        
        try {
            $id_especialidad = intval($_POST['id_especialidad']);  
            $fecha_cita = htmlspecialchars($_POST['fecha_cita']);  
            $descripcion = htmlspecialchars($_POST['descripcion']);  
            $tipo_form = $_POST['tipo_paciente']; 

            // 1. Insertar la cita en la tabla maestra
            $sql_citas = "INSERT INTO citas (id_especialidad, fecha_cita, descripcion, created_at, updated_at) 
                          VALUES (?, ?, ?, NOW(), NOW())";
            $stmt_citas = $conn->prepare($sql_citas);
            $stmt_citas->bind_param("iss", $id_especialidad, $fecha_cita, $descripcion);

            if ($stmt_citas->execute()) {
                $id_cita = $conn->insert_id;
                $tipo_paciente_bitacora = "";

              // --- CASO COMUNIDAD UPTM (EXTERNO) ---
if ($tipo_form === 'externo') {
    $cedula = htmlspecialchars($_POST['cedula_ext']);
    $nombre = htmlspecialchars($_POST['nombre_ext']);
    $apellido = htmlspecialchars($_POST['apellido_ext']);

    // 1. Buscamos si la cédula ya existe
    $check_uptm = $conn->prepare("SELECT id FROM comunidad_uptm WHERE cedula = ?");
    $check_uptm->bind_param("s", $cedula); 
    $check_uptm->execute();
    $res_uptm = $check_uptm->get_result();

    if ($row_ex = $res_uptm->fetch_assoc()) {
        // SI EXISTE: Reutilizamos el ID y actualizamos datos por si cambiaron
        $id_paciente_final = $row_ex['id'];
        $upd_ext = $conn->prepare("UPDATE comunidad_uptm SET nombre = ?, apellido = ?, updated_at = NOW() WHERE id = ?");
        $upd_ext->bind_param("ssi", $nombre, $apellido, $id_paciente_final);
        $upd_ext->execute();
        $upd_ext->close();
          } else {
        // NO EXISTE: Insertamos nuevo
        $ins_uptm = $conn->prepare("INSERT INTO comunidad_uptm (cedula, nombre, apellido, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
        $ins_uptm->bind_param("sss", $cedula, $nombre, $apellido);
        $ins_uptm->execute();
        $id_paciente_final = $conn->insert_id;
        $ins_uptm->close();
    }
    $check_uptm->close();

    // 2. Insertar relación en la intermedia
    $sql_relacion = "INSERT INTO citas_uptm (idcita, id_externo, created_at) VALUES (?, ?, NOW())";
    $stmt_rel = $conn->prepare($sql_relacion);
    $stmt_rel->bind_param("ii", $id_cita, $id_paciente_final);
    $stmt_rel->execute();
    $stmt_rel->close();
    
    $tipo_paciente_bitacora = "Comunidad UPTM (Externo)";
}
                // --- CASO AFILIADO / BENEFICIARIO (INTERNO) ---
                else {
                    if(!isset($_POST['id_paciente']) || empty($_POST['id_paciente'])) {
                        throw new Exception("Debe seleccionar un paciente de la lista.");
                    }

                    $id_paciente = intval($_POST['id_paciente']);

                    $sql_tipo = "SELECT 'Afiliado' AS tipo FROM afiliados WHERE id = ? UNION SELECT 'Beneficiario' AS tipo FROM beneficiarios WHERE id = ?";
                    $stmt_tipo = $conn->prepare($sql_tipo);
                    $stmt_tipo->bind_param("ii", $id_paciente, $id_paciente);
                    $stmt_tipo->execute();
                    $res_tipo = $stmt_tipo->get_result();
                    $row_tipo = $res_tipo->fetch_assoc();
                    
                    if (!$row_tipo) {
                        throw new Exception("Paciente no encontrado en la base de datos.");
                    }

                    $tipo_real = $row_tipo['tipo'];
                    $stmt_tipo->close();

                    if ($tipo_real === 'Afiliado') {
                        $sql_rel = "INSERT INTO citas_afil (idcita, id_afiliado) VALUES (?, ?)";
                    } else {
                        $sql_rel = "INSERT INTO citas_benef (idcita, id_beneficiario) VALUES (?, ?)";
                    }
                    
                    $stmt_rel = $conn->prepare($sql_rel);
                    $stmt_rel->bind_param("ii", $id_cita, $id_paciente);
                    $stmt_rel->execute();
                    $stmt_rel->close();
                    
                    $tipo_paciente_bitacora = $tipo_real;
                }

                // Bitácora (Solo si todo lo anterior salió bien)
                $usuario = $_SESSION['username'] ?? 'Sistema'; 
                $accion = "Registro de Cita";
                $desc_bitacora = "Se ha registrado una cita de tipo $tipo_paciente_bitacora para la fecha: $fecha_cita";
                registrarenBitacora($conn, $usuario, $accion, $desc_bitacora);

                echo json_encode(['success' => true, 'message' => "Cita de $tipo_paciente_bitacora registrada correctamente."]);

            } else {
                echo json_encode(['success' => false, 'message' => "Error al insertar la cita maestra: " . $stmt_citas->error]);
            
         }
            $stmt_citas->close();

        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {  
        echo json_encode(['success' => false, 'message' => "Faltan datos requeridos en el formulario."]);
    } 
} else {  
    echo json_encode(['success' => false, 'message' => "Método no permitido."]);
}  
$conn->close();  
?>