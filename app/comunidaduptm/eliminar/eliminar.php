<?php
session_start();
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula = mysqli_real_escape_string($conn, $_POST['cedula']);

    // Eliminar de comunidad_uptm
    // No referenciamos citas_uptm ya que en el SQL Dump vemos citas_uptm tiene CASCADE en delete.
    $sql_delete_comunidad = "DELETE FROM comunidad_uptm WHERE cedula = '$cedula'";

    if (mysqli_query($conn, $sql_delete_comunidad)) {
         // Registro en bitácora
         if (isset($_SESSION['username'])) {
            $usuario = $_SESSION['username'];
            $accion = "Eliminación de Comunidad UPTM";
            $descripcion = "Se eliminó a la persona con cédula (C.I: $cedula) de la comunidad.";
            $sql_bitacora = "INSERT INTO bitacora (usuario, accion, descripcion) VALUES ('$usuario', '$accion', '$descripcion')";
            mysqli_query($conn, $sql_bitacora);
        }

        echo json_encode(['success' => true, 'message' => 'Miembro de la comunidad eliminado exitosamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar: ' . mysqli_error($conn)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no válido.']);
}
?>
