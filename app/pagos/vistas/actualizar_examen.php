<?php
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id_examen'];
    $precio = $_POST['precio'];

    $sql = "UPDATE examenes SET precio = '$precio' WHERE ID_examen = '$id'";
    if (mysqli_query($conn, $sql)) {
        header("Location: /IPSPUPTM/home.php?vista=gestionexamenes&success=1");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
