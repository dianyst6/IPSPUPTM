<?php
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

if (isset($_GET['cedula'])) {
    $cedula_beneficiario = mysqli_real_escape_string($conn, $_GET['cedula']);

    // SQL que identifica al afiliado titular y calcula el consumo familiar
    $sql = "SELECT 
                p.nombre_plan,
                ex.nombre_examen,
                ex.ID_examen,
                cp_comp.cantidad_maxima,
                -- Subconsulta: Suma citas del afiliado titular + citas de sus beneficiarios
                ((SELECT COUNT(*) FROM citas_afil ca 
                  WHERE ca.id_afiliado = a.ID AND EXISTS (SELECT 1 FROM citas c WHERE c.id_cita = ca.idcita AND c.id_especialidad = ex.ID_especialidad_examenes)) 
                 + 
                 (SELECT COUNT(*) FROM citas_benef cb 
                  WHERE cb.id_beneficiario IN (SELECT ID FROM beneficiarios WHERE cedula_afil = a.ID) 
                  AND EXISTS (SELECT 1 FROM citas c WHERE c.id_cita = cb.idcita AND c.id_especialidad = ex.ID_especialidad_examenes))
                ) AS consumo_total_familiar
            FROM beneficiarios b
            JOIN afiliados a ON b.cedula_afil = a.ID
            JOIN contrato_plan cp ON a.cedula = cp.ID_afiliado_contrato
            JOIN planes p ON cp.ID_planes_contrato = p.ID_planes
            JOIN componentes_planes cp_comp ON p.ID_planes = cp_comp.ID_planes_componentes
            JOIN examenes ex ON cp_comp.ID_examen_componentes = ex.ID_examen
            WHERE b.cedula = ? AND cp.estado_contrato = 'Activo'";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $cedula_beneficiario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
        echo '<input type="hidden" id="nombre_plan_db" value="' . $data[0]['nombre_plan'] . '">';
        
        echo '<div class="table-responsive">
                <table class="table table-hover table-bordered border-dark">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>Servicio (Grupo Familiar)</th>
                            <th>Límite Plan</th>
                            <th>Consumo Total</th>
                            <th>Disponibles</th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($data as $row) {
            $disponibles = $row['cantidad_maxima'] - $row['consumo_total_familiar'];
            $clase = ($disponibles <= 0) ? 'table-danger text-danger fw-bold' : 'text-success fw-bold';

            echo "<tr>
                    <td>{$row['nombre_examen']}</td>
                    <td class='text-center'>{$row['cantidad_maxima']}</td>
                    <td class='text-center'>{$row['consumo_total_familiar']}</td>
                    <td class='text-center {$clase}'>{$disponibles}</td>
                  </tr>";
        }
        echo '</tbody></table></div>';
    } else {
        echo '<div class="alert alert-info text-center">No se detectó un contrato activo para el grupo familiar de este beneficiario.</div>';
    }
}
$conn->close();
?>