<?php
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

header('Content-Type: application/json');

if (isset($_POST['correo'])) {
    $correo = mysqli_real_escape_string($conn, $_POST['correo']);
    $cedula_ignorada = isset($_POST['cedula_ignorada']) ? mysqli_real_escape_string($conn, $_POST['cedula_ignorada']) : '';
    
    $query = "SELECT COUNT(*) as count FROM persona WHERE correo = '$correo'";
    if (!empty($cedula_ignorada)) {
        $query .= " AND cedula != '$cedula_ignorada'";
    }
    
    $result = mysqli_query($conn, $query);
    $existe = false;
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $existe = $row['count'] > 0;
    }

    echo json_encode(['existe' => $existe]);
} else {
    echo json_encode(['error' => 'Correo no proporcionado']);
}
?>
