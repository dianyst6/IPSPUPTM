<?php
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

// Obtener una cédula válida para probar
$qa = mysqli_query($conn, "SELECT p.cedula FROM persona p JOIN afiliados a ON p.cedula = a.cedula JOIN contrato_plan cp ON cp.ID_afiliado_contrato = a.cedula WHERE cp.estado_contrato = 'Activo' LIMIT 1");
$cedula_test = mysqli_fetch_assoc($qa)['cedula'] ?? '';

echo "CEDULA PARA TEST: $cedula_test \n";

// Simular el GET
$_GET['cedula'] = $cedula_test;
ob_start();
include 'C:/xampp/htdocs/IPSPUPTM/app/pagos/get_afiliado_plan_limits.php';
$output = ob_get_clean();

echo "SALIDA DEL AJAX: \n";
echo $output;
?>
