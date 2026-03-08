<?php
header('Content-Type: application/json');
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

// Supongamos que recibes la cédula para buscar
if (isset($_POST['cedula'])) {
    $cedula = $_POST['cedula']; 

    // Consulta directa y sencilla
    $sql = "SELECT cedula, nombre, apellido FROM comunidad_uptm WHERE cedula = ?";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $cedula); // "s" porque la cédula suele ser string
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Mandamos los datos de la fila ($row)
            echo json_encode($row);
        } else {
            echo json_encode(["error" => "No existe nadie con esa cédula"]);
        }
        $stmt->close();
    }
} else {
    echo json_encode(["error" => "Falta la cédula"]);
}
$conn->close();
?>