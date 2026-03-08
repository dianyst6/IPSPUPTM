<div class="mt-3 m-3">
    <h1 class="text-center">Gestión de Planes</h1>
    <br>
    <div class="table-responsive">
        <table class="table table-sm table-striped table-hover mx-auto" id="tablaContratos" width="100%"
            cellspacing="0">
            <thead class="table-dark">
                <tr>
                    <th>Afiliado</th>
                    <th>Plan</th>
                    <th>Monto Total</th>
                    <th>Frecuencia</th>
                    <th>Día Pago</th>
                    <th>Vigencia</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    require 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

                    $sql = "SELECT cp.*, per.nombre, per.apellido, pl.nombre_plan 
                            FROM contrato_plan cp
                            INNER JOIN persona per ON cp.ID_afiliado_contrato = per.cedula
                            INNER JOIN planes pl ON cp.ID_planes_contrato = pl.ID_planes
                            ORDER BY cp.fecha_inicio DESC";

                    $resultado = mysqli_query($conn, $sql);

                    while ($row = mysqli_fetch_assoc($resultado)) {
                        // Lógica para color del badge de estado
                        $badgeColor = ($row['estado_contrato'] == 'Activo') ? 'bg-success' : 'bg-danger';
                        if ($row['estado_contrato'] == 'Vencido') $badgeColor = 'bg-warning text-dark';
                    ?>
                <tr>
                    <td><?php echo $row['nombre'] . " " . $row['apellido']; ?> <br>
                        <small class="text-muted">ID: <?php echo $row['ID_afiliado_contrato']; ?></small>
                    </td>
                    <td><?php echo $row['nombre_plan']; ?></td>
                    <td class="fw-bold">$ <?php echo number_format($row['monto_total'], 2); ?></td>
                    <td><?php echo $row['frecuencia_pago']; ?></td>
                    <td class="text-center"><?php echo $row['dia_pago_mensual']; ?></td>
                    <td>
                        <small>Desde: <?php echo date('d/m/Y', strtotime($row['fecha_inicio'])); ?></small><br>
                        <small>Hasta: <?php echo date('d/m/Y', strtotime($row['fecha_fin'])); ?></small>
                    </td>
                    <td>
                        <span class="badge <?php echo $badgeColor; ?>">
                            <?php echo $row['estado_contrato']; ?>
                        </span>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-outline-info" title="Ver Detalles">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-primary" title="Editar Contrato">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" title="Eliminar Contrato"
                                onclick="eliminarContrato(<?php echo $row['ID_contrato']; ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    
    <script>
function eliminarContrato(id) {
    if (confirm("¿Está seguro de eliminar este contrato? Esta acción no se puede deshacer y podría fallar si hay pagos registrados.")) {
        // Redirigir al archivo procesador
        window.location.href = "/IPSPUPTM/app/pagos/eliminar_contrato.php?id=" + id;
    }
}
</script>