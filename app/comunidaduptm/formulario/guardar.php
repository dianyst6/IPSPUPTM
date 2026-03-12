<?php
session_start();
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula = mysqli_real_escape_string($conn, $_POST['cedula']);
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conn, $_POST['apellido']);

    // Verificar si la cedula ya existe en la tabla
    $check_query = "SELECT COUNT(*) as count FROM comunidad_uptm WHERE cedula = '$cedula'";
    $check_result = mysqli_query($conn, $check_query);
    $row = mysqli_fetch_assoc($check_result);

    if ($row['count'] > 0) {
        echo json_encode(['success' => false, 'message' => 'La cédula ya está registrada en la Comunidad UPTM.']);
        exit;
    }

    $sql = "INSERT INTO comunidad_uptm (cedula, nombre, apellido) VALUES ('$cedula', '$nombre', '$apellido')";

    if (mysqli_query($conn, $sql)) {
         // Registro en bitácora
         if (isset($_SESSION['username'])) {
            $usuario = $_SESSION['username'];
            $accion = "Registro de Comunidad UPTM";
            $descripcion = "Se registró a la persona en comunidad: $nombre $apellido (C.I: $cedula)";
            $sql_bitacora = "INSERT INTO bitacora (usuario, accion, descripcion) VALUES ('$usuario', '$accion', '$descripcion')";
            mysqli_query($conn, $sql_bitacora);
        }

        echo json_encode(['success' => true, 'message' => 'Miembro de la comunidad registrado exitosamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al registrar: ' . mysqli_error($conn)]);
    }
}
?>
