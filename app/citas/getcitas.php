<?php
header('Content-Type: application/json');
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

if (isset($_POST['id_cita'])) {
    $id_cita = intval($_POST['id_cita']); 
    $sql = "
        SELECT
            c.id_cita,
            c.fecha_cita,
            c.descripcion,
            c.id_especialidad,
            CASE
                WHEN ca.idcita IS NOT NULL THEN 'interno'
                WHEN cb.idcita IS NOT NULL THEN 'interno'
                WHEN cu.idcita IS NOT NULL THEN 'externo'
            END AS tipo_origen,
            -- Datos del paciente externo (Comunidad UPTM)
            u.nombre AS nombre_ext,
            u.apellido AS apellido_ext,
            u.cedula AS cedula_ext,
            -- ID para el select de internos (si aplica)
            CASE
                WHEN ca.idcita IS NOT NULL THEN a.id
                WHEN cb.idcita IS NOT NULL THEN b.id
                ELSE NULL
            END AS id_paciente
        FROM citas c
        LEFT JOIN citas_afil ca ON c.id_cita = ca.idcita
        LEFT JOIN afiliados a ON ca.id_afiliado = a.id
        LEFT JOIN citas_benef cb ON c.id_cita = cb.idcita
        LEFT JOIN beneficiarios b ON cb.id_beneficiario = b.id
        LEFT JOIN citas_uptm cu ON c.id_cita = cu.idcita
        -- El JOIN correcto usando 'id' como confirmaste
        LEFT JOIN comunidad_uptm u ON cu.id_externo = u.id 
        LEFT JOIN especialidades e ON c.id_especialidad = e.id_especialidad
        WHERE c.id_cita = ?";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $id_cita);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Formateo para datetime-local (importante para que el input lo reconozca)
            $row['fecha_cita'] = date('Y-m-d\TH:i', strtotime($row['fecha_cita']));
            echo json_encode([$row]);
        } else {
            echo json_encode(["error" => "No se encontró la cita con ID: " . $id_cita]);
        }
        $stmt->close();
    } else {
        echo json_encode(["error" => "Error al preparar la consulta"]);
    }
} else {
    echo json_encode(["error" => "No se recibió ID"]);
}
$conn->close();
?>