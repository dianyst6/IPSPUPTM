<?php
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

if (isset($_GET['cedula'])) {
    $cedula = mysqli_real_escape_string($conn, $_GET['cedula']);

    // Consulta que suma el consumo de todo el núcleo familiar
    $sql = "SELECT 
                p.nombre_plan,
                ex.nombre_examen,
                cp_comp.cantidad_maxima,
                -- SUMA TOTAL: Citas del titular + Citas de sus beneficiarios
                ((SELECT COUNT(*) 
                  FROM citas_afil ca
                  INNER JOIN citas c ON ca.idcita = c.id_cita
                  INNER JOIN examenes e ON c.id_especialidad = e.ID_especialidad_examenes
                  WHERE ca.id_afiliado = a.ID 
                  AND e.ID_examen = ex.ID_examen)
                 +
                 (SELECT COUNT(*) 
                  FROM citas_benef cb
                  INNER JOIN citas c ON cb.idcita = c.id_cita
                  INNER JOIN examenes e ON c.id_especialidad = e.ID_especialidad_examenes
                  WHERE cb.id_beneficiario IN (SELECT ID FROM beneficiarios WHERE cedula_afil = a.ID)
                  AND e.ID_examen = ex.ID_examen)
                ) AS consumo_total_grupo
            FROM afiliados a
            INNER JOIN contrato_plan cp ON a.cedula = cp.ID_afiliado_contrato
            INNER JOIN planes p ON cp.ID_planes_contrato = p.ID_planes
            INNER JOIN componentes_planes cp_comp ON p.ID_planes = cp_comp.ID_planes_componentes
            INNER JOIN examenes ex ON cp_comp.ID_examen_componentes = ex.ID_examen
            WHERE a.cedula = ? AND cp.estado_contrato = 'Activo'";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $cedula);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
        echo '<input type="hidden" id="nombre_plan_db" value="' . $data[0]['nombre_plan'] . '">';

        echo '<div class="table-responsive">
                <table class="table table-hover table-bordered border-dark">
                    <thead class="table-dark">
                        <tr>
                            <th>Examen Incluido</th>
                            <th>Límite (Familiar)</th>
                            <th>Consumo Grupo</th>
                            <th>Disponibles</th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($data as $row) {
            $disponibles = $row['cantidad_maxima'] - $row['consumo_total_grupo'];
            $text_class = ($disponibles <= 0) ? 'text-danger fw-bold' : 'text-success fw-bold';
            $bg_row = ($disponibles <= 0) ? 'table-danger' : '';

            echo "<tr class='{$bg_row}'>
                    <td>{$row['nombre_examen']}</td>
                    <td class='text-center'>{$row['cantidad_maxima']}</td>
                    <td class='text-center'>{$row['consumo_total_grupo']}</td>
                    <td class='text-center {$text_class}'>{$disponibles}</td>
                  </tr>";
        }
        echo '</tbody></table></div>';
    } else {
        echo '<div class="alert alert-warning text-center">No se encontró contrato activo para este núcleo familiar.</div>';
    }
    $stmt->close();
}
$conn->close();
?>