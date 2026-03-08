<?php
session_start();

require 'C:/xampp/htdocs/IPSPUPTM/config/database.php';
include 'C:/xampp/htdocs/IPSPUPTM/app/configuracion/bitacora/bitacora.php';

// Verifica que se ha enviado el formulario por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Validamos que los campos esenciales estén presentes
    if (isset($_POST['cedula'], $_POST['nombre'], $_POST['id_planes_contrato'], $_POST['fecha_inicio'])) {
        
        // --- 1. Recolección de Datos Personales ---
        $cedula = htmlspecialchars($_POST['cedula']);
        $nombre = htmlspecialchars($_POST['nombre']);
        $apellido = htmlspecialchars($_POST['apellido']);
        $fechanacimiento = htmlspecialchars($_POST['fechanacimiento']);
        $genero = htmlspecialchars($_POST['genero']);
        $telefono = htmlspecialchars($_POST['telefono']);
        $correo = htmlspecialchars($_POST['correo']);
        $ocupacion = htmlspecialchars($_POST['ocupacion']);

        // --- 2. Recolección de Datos del Contrato ---
        $id_plan = $_POST['id_planes_contrato'];
        $monto_total = $_POST['monto_total'];
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];
        $frecuencia = $_POST['frecuencia_pago'];
        $dia_pago = $_POST['dia_pago_mensual'];
        $estado_contrato = $_POST['estado_contrato'];

        // --- 3. Inicio de Operación Segura (Transacción) ---
        $conn->begin_transaction();

        try {
            // A. Verificar si la PERSONA existe
            $sql_check_p = "SELECT cedula FROM persona WHERE cedula = ?";
            $stmt_check_p = $conn->prepare($sql_check_p);
            $stmt_check_p->bind_param("s", $cedula);
            $stmt_check_p->execute();
            $res_p = $stmt_check_p->get_result();

            if ($res_p->num_rows === 0) {
                // Si no existe, crear registro en persona
                $sql_persona = "INSERT INTO persona (cedula, nombre, apellido, fechanacimiento, genero, telefono, correo, ocupacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt_p = $conn->prepare($sql_persona);
                $stmt_p->bind_param("ssssssss", $cedula, $nombre, $apellido, $fechanacimiento, $genero, $telefono, $correo, $ocupacion);
                $stmt_p->execute();
                $stmt_p->close();
            }
            $stmt_check_p->close();

            // B. Verificar si ya es AFILIADO (Evita el error Duplicate Entry)
            $sql_check_a = "SELECT cedula FROM afiliados WHERE cedula = ?";
            $stmt_check_a = $conn->prepare($sql_check_a);
            $stmt_check_a->bind_param("s", $cedula);
            $stmt_check_a->execute();
            $res_a = $stmt_check_a->get_result();

            if ($res_a->num_rows > 0) {
                // Si ya existe en la tabla afiliados, cancelamos todo
                throw new Exception("Error: El usuario con cédula $cedula ya está registrado como afiliado.");
            }
            $stmt_check_a->close();

            // C. Insertar en tabla AFILIADOS
            $sql_afiliado = "INSERT INTO afiliados (cedula, created_at, updated_at) VALUES (?, NOW(), NOW())";
            $stmt_a = $conn->prepare($sql_afiliado);
            $stmt_a->bind_param("s", $cedula);
            $stmt_a->execute();
            $stmt_a->close();

            // D. Insertar en tabla CONTRATO_PLAN
            $sql_contrato = "INSERT INTO contrato_plan (ID_planes_contrato, ID_afiliado_contrato, fecha_inicio, fecha_fin, monto_total, frecuencia_pago, dia_pago_mensual, estado_contrato) 
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_c = $conn->prepare($sql_contrato);
            // El orden de tipos en bind_param es: i (int), s (string), s, s, d (decimal/double), s, i (int), s
            $stmt_c->bind_param("isssdsss", $id_plan, $cedula, $fecha_inicio, $fecha_fin, $monto_total, $frecuencia, $dia_pago, $estado_contrato);
            $stmt_c->execute();
            $stmt_c->close();

            // --- 4. Finalizar Transacción ---
            $conn->commit();

            // Registro en Bitácora
            $usuario_log = $_SESSION['username'] ?? 'Sistema';
            registrarEnBitacora($conn, $usuario_log, "Registro Integral", "Afiliado y Plan creados: $cedula");

            $_SESSION['flash_msg'] = "Afiliado y Contrato registrados exitosamente.";
            $_SESSION['flash_type'] = "success";

            header("Location: /IPSPUPTM/home.php?vista=afiliados");
            exit();

        } catch (Exception $e) {
            // Si algo falla, se deshacen todos los cambios
            $conn->rollback();
            $_SESSION['flash_msg'] = $e->getMessage();
            $_SESSION['flash_type'] = "danger";
            header("Location: /IPSPUPTM/home.php?vista=afiliados");
            exit();
        }

    } else {
        echo "Faltan datos requeridos en el formulario.";
    }
} else {
    echo "Método de solicitud no válido.";
}

$conn->close();
?>