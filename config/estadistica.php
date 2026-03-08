<?php
// Incluir el archivo de conexión
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

// Inicializar las variables
$total_afiliados = 0;
$total_beneficiarios = 0;
$total_citas = 0;

// Consultar el total de afiliados
$sql_afiliados = "SELECT COUNT(*) AS total_afiliados FROM afiliados";
$result_afiliados = $conn->query($sql_afiliados);
if ($result_afiliados->num_rows > 0) {
    $row = $result_afiliados->fetch_assoc();
    $total_afiliados = $row['total_afiliados'];
}

// Consultar el total de beneficiarios
$sql_beneficiarios = "SELECT COUNT(*) AS total_beneficiarios FROM beneficiarios";
$result_beneficiarios = $conn->query($sql_beneficiarios);
if ($result_beneficiarios->num_rows > 0) {
    $row = $result_beneficiarios->fetch_assoc();
    $total_beneficiarios = $row['total_beneficiarios'];
}

// Consultar el total de citas
$sql_citas = "SELECT COUNT(*) AS total_citas FROM citas";
$result_citas = $conn->query($sql_citas);
if ($result_citas->num_rows > 0) {
    $row = $result_citas->fetch_assoc();
    $total_citas = $row['total_citas'];
}

// Cerrar la conexión a la base de datos
$conn->close();
?>