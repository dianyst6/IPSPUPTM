<?php
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

if (isset($_GET['cedula'])) {
    $cedula_beneficiario = mysqli_real_escape_string($conn, $_GET['cedula']);

    // SQL que identifica al afiliado titular y calcula el consumo familiar (Soporta Categorías y Exámenes)
    $sql = "SELECT 
                p.nombre_plan,
                COALESCE(ex.nombre_examen, cat.nombre_categoria) AS nombre_item,
                cp_comp.cantidad_maxima,
                (
                    SELECT COUNT(*)
                    FROM citas_examenes ce
                    INNER JOIN citas c ON ce.id_cita = c.id_cita
                    INNER JOIN examenes e_inner ON ce.id_examen = e_inner.ID_examen
                    WHERE (
                        (cp_comp.ID_examen_componentes IS NOT NULL AND ce.id_examen = cp_comp.ID_examen_componentes)
                        OR
                        (cp_comp.id_categoria_componente IS NOT NULL AND e_inner.id_categoria = cp_comp.id_categoria_componente)
                    )
                    AND c.estado_pago = 'Deducida de Póliza'
                    AND (
                        c.id_cita IN (SELECT idcita FROM citas_afil WHERE id_afiliado = a.ID)
                        OR 
                        c.id_cita IN (SELECT idcita FROM citas_benef WHERE id_beneficiario IN (SELECT ID FROM beneficiarios WHERE cedula_afil = a.ID))
                    )
                ) AS consumo_total_familiar
            FROM beneficiarios b
            JOIN afiliados a ON b.cedula_afil = a.ID
            JOIN contrato_plan cp ON a.cedula = cp.ID_afiliado_contrato
            JOIN planes p ON cp.ID_planes_contrato = p.ID_planes
            JOIN componentes_planes cp_comp ON p.ID_planes = cp_comp.ID_planes_componentes
            LEFT JOIN examenes ex ON cp_comp.ID_examen_componentes = ex.ID_examen
            LEFT JOIN categorias_examenes cat ON cp_comp.id_categoria_componente = cat.id_categoria
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
            $cantidad_limite = $row['cantidad_maxima'];
            $consumo = $row['consumo_total_familiar'];
            
            // Si el límite es NULL o vacío, se considera ILIMITADO
            if (is_null($cantidad_limite) || $cantidad_limite === "" || $cantidad_limite === "NULL") {
                $limite_display = '<span class="badge bg-secondary">Ilimitado</span>';
                $disponibles_display = '<span class="badge bg-success">Ilimitado</span>';
                $clase = 'text-success fw-bold';
            } else {
                $disponibles = intval($cantidad_limite) - $consumo;
                $limite_display = $cantidad_limite;
                $disponibles_display = $disponibles;
                $clase = ($disponibles <= 0) ? 'table-danger text-danger fw-bold' : 'text-success fw-bold';
            }

            echo "<tr>
                    <td>{$row['nombre_item']}</td>
                    <td class='text-center'>{$limite_display}</td>
                    <td class='text-center'>{$consumo}</td>
                    <td class='text-center {$clase}'>{$disponibles_display}</td>
                  </tr>";
        }
        echo '</tbody></table></div>';
    } else {
        echo '<div class="alert alert-info text-center">No se detectó un contrato activo para el grupo familiar de este beneficiario.</div>';
    }
}
$conn->close();
?>