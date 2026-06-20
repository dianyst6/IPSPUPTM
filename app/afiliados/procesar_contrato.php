<?php
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cedula = $_POST['cedula_afiliado'];
    $id_plan = $_POST['id_plan'];
    $monto = $_POST['monto_total'];
    $estado = $_POST['estado'];

    // Insertar en contrato_plan según tu estructura
    $query = "INSERT INTO contrato_plan (ID_afiliado_contrato, ID_planes_contrato, monto_total, estado_contrato) 
              VALUES ('$cedula', '$id_plan', '$monto', '$estado')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Plan contratado exitosamente'); window.location.href='gestion_afiliados.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
