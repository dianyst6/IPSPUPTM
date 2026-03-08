<?php
require 'C:/xampp/htdocs/IPSPUPTM/config/database.php';
$cedula = $_GET['cedula'] ?? '';

$sql = "SELECT nombre, apellido FROM comunidad_uptm WHERE cedula = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $cedula);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(['exists' => true, 'nombre' => $row['nombre'], 'apellido' => $row['apellido']]);
} else {
    echo json_encode(['exists' => false]);
}