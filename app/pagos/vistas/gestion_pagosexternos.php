<?php
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

// Configuración de paginación
$rowsPerPage = 15;
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($currentPage - 1) * $rowsPerPage;

// Detectar el filtro (Pendientes por defecto)
$filtro = isset($_GET['estado']) ? $_GET['estado'] : 'pendientes';

// Construir la consulta según el filtro
if ($filtro == 'pendientes') {
    // Solo los que NO están en la tabla de pagos
    $whereSQL = "WHERE p.id_pago_ext IS NULL";
} else {
    // Todos (Historial)
    $whereSQL = ""; 
}

// Consulta principal con JOINS
// Ajusta 'comunidad_uptm' y 'citas_uptm' según tus nombres reales
$sql = "SELECT c.id_cita, 
               CONCAT(u.nombre, ' ', u.apellido) AS nombre_paciente, 
               u.cedula, 
               e.nombre_especialidad, 
               c.fecha_cita,
               p.monto_final,
               p.id_pago_ext
        FROM citas c
        INNER JOIN citas_uptm h ON c.id_cita = h.idcita
        INNER JOIN comunidad_uptm u ON h.id_externo = u.id
        INNER JOIN especialidades e ON c.id_especialidad = e.id_especialidad
        LEFT JOIN pagos_externos p ON c.id_cita = p.id_cita
        $whereSQL
        LIMIT $offset, $rowsPerPage";

$citas = $conn->query($sql);

// Calcular total de páginas
$totalQuery = "SELECT COUNT(*) as total FROM citas c 
               INNER JOIN citas_uptm h ON c.id_cita = h.idcita
               LEFT JOIN pagos_externos p ON c.id_cita = p.id_cita
               $whereSQL";
$totalResult = $conn->query($totalQuery);
$totalRows = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $rowsPerPage);
?>

<div class="card shadow-lg">
    <div class="card-body p-4">
        <h1 class="fw-bold text-center" style="color: #062974;">Gestión de Pagos Externos</h1>
        <hr class="mx-auto" style="width: 50px; height: 3px; background-color: #062974;">

        <div class="row mt-4 align-items-center">
            <div class="col-auto">
                <div class="btn-group" role="group">
                    <a href="/IPSPUPTM/home.php?vista=gestionpagosexternos&estado=pendientes" 
                       class="btn <?php echo ($filtro == 'pendientes') ? 'btn-warning' : 'btn-outline-warning'; ?>">
                        <i class="fas fa-clock"></i> Pagos Pendientes
                    </a>
                    <a href="/IPSPUPTM/home.php?vista=gestionpagosexternos&estado=todos" 
                       class="btn <?php echo ($filtro == 'todos') ? 'btn-primary' : 'btn-outline-primary'; ?>">
                        <i class="fas fa-history"></i> Historial de Pagos
                    </a>
                </div>
            </div>

            <div class="col text-end">
                <input type="text" id="search" class="form-control w-auto d-inline-block" placeholder="Buscar paciente...">
            </div>
        </div>

        <div class="table-responsive mt-4">
            <h4 class="mb-3">
                <?php echo ($filtro == 'pendientes') ? 'Citas por Cobrar' : 'Registro Histórico de Pagos'; ?>
            </h4>
            
            <table class="table table-sm table-striped table-hover shadow-sm">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Paciente</th>
                        <th>Cédula</th>
                        <th>Especialidad</th>
                        <th>Fecha Cita</th>
                        <th>Monto Final</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php if ($citas->num_rows > 0) { ?>
                        <?php while ($row = $citas->fetch_assoc()) { 
                            // Lógica para determinar si ya pagó
                            $pagado = !empty($row['id_pago_ext']); 
                        ?>
                        <tr>
                            <td class="text-start ps-3"><?php echo htmlspecialchars($row['nombre_paciente']); ?></td>
                            <td><?php echo htmlspecialchars($row['cedula']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre_especialidad']); ?></td>
                            <td><?php echo date('d-m-Y', strtotime($row['fecha_cita'])); ?></td>
                            
                            <td class="fw-bold text-success">
                                <?php echo $pagado ? $row['monto_final'] . " $" : "---"; ?>
                            </td>

                            <td>
                                <?php if($pagado): ?>
                                    <span class="badge bg-success">Pagado</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Pendiente</span>
                                <?php endif; ?>
                            </td>

                           <td>
                                <?php if (!$pagado): ?>
                                   <button type="button" 
                                    class="btn btn-success btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#pagomodal"
                                    data-bs-idcita="<?= $row['id_cita']; ?>" 
                                    data-bs-nombre="<?= $row['nombre_paciente']; ?>"
                                    data-bs-costobase=""
                                    data-bs-descuento=""> 
                                Pagar
                            </button>   
                                <?php else: ?>
                                    <a href="#" class="btn btn-info btn-sm text-white">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                    <a href="#" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="7" class="py-4 text-center text-muted">No hay registros que coincidan con el filtro.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                <a href="?vista=gestionpagosexternos&estado=<?php echo $filtro; ?>&page=<?php echo $i; ?>"
                   class="btn btn-sm <?php echo ($i == $currentPage) ? 'btn-secondary' : 'btn-primary'; ?> mx-1">
                   <?php echo $i; ?>
                </a>
            <?php } ?>
        </div>
    </div>
</div>

<?php include 'C:/xampp/htdocs/IPSPUPTM/app/pagos/modales/pagosexternos/formulario/formulariomodal.php'; ?>
<?php include 'C:/xampp/htdocs/IPSPUPTM/app/citas/modales/formulario/formulariomodal.php'; ?>
<?php include 'C:/xampp/htdocs/IPSPUPTM/app/citas/modales/actualizar/editmodal.php'; ?>

<script src="/IPSPUPTM/assets/js/accionescitas.js"></script>