<?php
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

$sql = "DELETE FROM bitacora";

if ($conn->query($sql) === TRUE) {
    echo "Todos los registros han sido eliminados exitosamente.";
} else {
    echo "Error al eliminar registros: " . $conn->error;
}

$conn->close();
?>