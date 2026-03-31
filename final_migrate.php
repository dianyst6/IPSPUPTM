
<?php
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';
$sql = "ALTER TABLE consumo_plan ADD COLUMN id_categoria_externa INT(11) DEFAULT NULL AFTER nombre_estudio_externo";
$res = mysqli_query($conn, $sql);
echo "Final migration: " . ($res ? "Éxito" : "Falló: " . mysqli_error($conn)) . "\n";
?>
