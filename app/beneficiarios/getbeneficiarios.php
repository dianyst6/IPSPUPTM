<?php
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

if (isset($_POST['cedula'])) {
    $cedula = htmlspecialchars($_POST['cedula']);

    $sql = "SELECT 
                p.cedula, 
                p.nombre, 
                p.apellido, 
                p.fechanacimiento, 
                p.genero, 
                p.telefono, 
                p.correo, 
                p.ocupacion, 
                b.created_at, 
                b.updated_at, 
                b.parentesco,
                a.id AS id_afiliado, -- Incluimos el ID del afiliado
                CONCAT(pa.nombre, ' ', pa.apellido) AS afiliado_nombre_completo
            FROM 
                beneficiarios b
            JOIN 
                persona p ON b.cedula = p.cedula
            JOIN 
                afiliados a ON b.cedula_afil = a.id
            JOIN 
                persona pa ON a.cedula = pa.cedula
            WHERE 
                b.cedula = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $cedula);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $beneficiarios = $result->fetch_assoc();
            echo json_encode($beneficiarios, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["error" => "Beneficiario no encontrado"], JSON_UNESCAPED_UNICODE);
        }

        $stmt->close();
    } else {
        echo json_encode(["error" => "Error al preparar la consulta"], JSON_UNESCAPED_UNICODE);
    }
} else {
    echo json_encode(["error" => "Cédula no proporcionada"], JSON_UNESCAPED_UNICODE);
}

$conn->close();
?>