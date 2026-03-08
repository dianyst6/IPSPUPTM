<div class="card shadow-lg">
    <div class="cont-general">
        <div class="card-body">
            <div class="mt-3 m-3">
                 <h1 class="fw-bold text-center" style="color: #062974;">Gestión de Pagos</h1>
            <hr class="mx-auto" style="width: 50px; height: 3px; background-color: #062974;">
                <br>

                <div class="row mt-3">
                    <div class="col-auto">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#modalPago">
                            <i class="fas fa-plus-circle"></i> Registrar Pago
                        </button>
                    </div>
                    <div class="col text-end mt-2">
                        <input type="text" id="searchPagos" class="form-control w-auto d-inline-block"
                            placeholder="Buscar paciente...">
                    </div>
                </div>

                <div class="table-responsive mt-3">
                    <h4>Pagos Registrados</h4>
                    <table class="table table-sm table-striped table-hover mx-auto">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>Fecha</th>
                                <th>Nombre del Paciente</th>
                                <th>Plan</th>
                                <th>N° Cuota</th>
                                <th>Monto Pagado</th>
                                <th>Método</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <?php
                            include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';
            // Consulta para traer los pagos realizados con el nombre de la persona
            $query = "SELECT 
                        pc.ID_pago,
                        pc.fecha_pago,
                        pc.monto_cuota,
                        pc.numero_cuota,
                        pc.metodo_pago,
                        p.nombre, 
                        p.apellido,
                        pl.nombre_plan
                      FROM pagos_contrato pc
                      INNER JOIN contrato_plan cp ON pc.ID_contrato = cp.ID_contrato
                      INNER JOIN afiliados af ON cp.ID_afiliado_contrato = af.cedula
                      INNER JOIN persona p ON af.cedula = p.cedula
                      INNER JOIN planes pl ON cp.ID_planes_contrato = pl.ID_planes
                      ORDER BY pc.fecha_pago DESC"; // Los más recientes primero

            $result = mysqli_query($conn, $query);

            if (!$result) {
                echo "<tr><td colspan='7'>Error: " . mysqli_error($conn) . "</td></tr>";
            } elseif (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . date('d/m/Y', strtotime($row['fecha_pago'])) . "</td>";
                    echo "<td class='text-start'>" . $row['nombre'] . " " . $row['apellido'] . "</td>";
                    echo "<td>" . $row['nombre_plan'] . "</td>";
                    echo "<td>Cuota #" . $row['numero_cuota'] . "</td>";
                    echo "<td><strong>$ " . number_format($row['monto_cuota'], 2) . "</strong></td>";
                    echo "<td>" . $row['metodo_pago'] . "</td>";
                    echo "<td>
                            <a href='ver_recibo.php?id=" . $row['ID_pago'] . "' class='btn btn-info btn-sm' title='Ver Recibo'><i class='fas fa-file-invoice'></i></a>
                          
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No se han registrado pagos todavía.</td></tr>";
            }
            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<?php include 'C:/xampp/htdocs/IPSPUPTM/app/pagos/modales/modal_registrar_pago.php'; ?>

<?php include 'C:/xampp/htdocs/IPSPUPTM/app/pagos/modales/procesar_pago.php'; ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchPagos');
    const tableRows = document.querySelectorAll('tbody tr');

    searchInput.addEventListener('keyup', function(e) {
        const text = e.target.value.toLowerCase();

        tableRows.forEach(row => {
            // Buscamos específicamente en la segunda celda (Índice 1: Nombre del Paciente)
            const nombrePaciente = row.cells[1].textContent.toLowerCase();
            
            if (nombrePaciente.includes(text)) {
                row.style.display = ''; // Muestra la fila
            } else {
                row.style.display = 'none'; // Oculta la fila
            }
        });
    });
});
</script>