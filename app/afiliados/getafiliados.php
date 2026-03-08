<?php
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

if (isset($_POST['cedula'])) {
    $cedula = htmlspecialchars($_POST['cedula']); // Limpia la cédula para mayor seguridad


    $sql = "SELECT 
                p.cedula, 
                p.nombre, 
                p.apellido, 
                p.fechanacimiento, 
                p.genero, 
                p.telefono, 
                p.correo, 
                p.ocupacion, 
                a.created_at, 
                a.updated_at 
            FROM 
                afiliados a
            JOIN 
                persona p ON a.cedula = p.cedula
            WHERE 
                a.cedula = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $cedula);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $afiliados = $result->fetch_assoc();
            // Devuelve todos los datos en formato JSON
            echo json_encode($afiliados);
        } else {
            echo json_encode(["error" => "Afiliado no encontrado"]);
        }

        $stmt->close();
    } else {
        echo json_encode(["error" => "Error al preparar la consulta"]);
    }
} else {
    echo json_encode(["error" => "Cédula no proporcionada"]);
}

$conn->close();
?>