<?php
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

$rowsPerPage = 15; // Número de registros por página
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1; // Página actual
$offset = ($currentPage - 1) * $rowsPerPage;

try {
    // Consulta para obtener datos de beneficiarios junto con información de personas y afiliados
    $sqlBeneficiarios = "
        SELECT 
            b.cedula, 
            p_b.nombre AS nombre_beneficiario, 
            p_b.apellido AS apellido_beneficiario, 
            CONCAT(p_a.nombre, ' ', p_a.apellido) AS nombre_afiliado, 
            b.created_at, 
            b.updated_at 
        FROM beneficiarios b
        JOIN persona p_b ON b.cedula = p_b.cedula -- Datos del beneficiario
        JOIN afiliados a ON b.cedula_afil = a.ID -- Relación entre beneficiarios y afiliados
        JOIN persona p_a ON a.cedula = p_a.cedula -- Datos del afiliado
        LIMIT $rowsPerPage OFFSET $offset
    ";
    $beneficiarios = $conn->query($sqlBeneficiarios);

    // Validación de errores en la consulta
    if (!$beneficiarios) {
        throw new Exception("Error en la consulta de beneficiarios: " . $conn->error);
    }

    // Obtener el total de filas para calcular páginas
    $totalRowsResult = $conn->query("SELECT COUNT(*) AS total FROM beneficiarios");
    if (!$totalRowsResult) {
        throw new Exception("Error en la consulta del total de beneficiarios: " . $conn->error);
    }

    $totalRows = $totalRowsResult->fetch_assoc()['total'];
    $totalPages = ceil($totalRows / $rowsPerPage);

    // Consulta para obtener la lista de afiliados para el formulario
    $sql_afiliados = "
        SELECT a.ID, CONCAT(p.nombre, ' ', p.apellido) AS nombre_afiliado 
        FROM afiliados a
        JOIN persona p ON a.cedula = p.cedula
        ORDER BY p.nombre ASC
    ";
    $result_afiliados = $conn->query($sql_afiliados);
    if (!$result_afiliados) {
        throw new Exception("Error en la consulta de afiliados: " . $conn->error);
    }

} catch (Exception $e) {
    // Manejo de errores
    echo "Ocurrió un error: " . $e->getMessage();
    exit();
}
?>

<div class="card shadow-lg">
    <div class="cont-general">

        <div class="mt-3 m-3 text-justify">
             <h1 class="fw-bold text-center" style="color: #062974;">Beneficiarios</h1>
            <hr class="mx-auto" style="width: 50px; height: 3px; background-color: #062974;">

            <!-- Contenedor para el botón y el input de búsqueda -->
            <div class="row mt-4 align-items-center">
                <div class="col-auto">
                    <!-- Botón Agregar Beneficiario alineado a la izquierda -->
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formulariomodal">
                        <i class="fas fa-plus-circle"></i> Agregar beneficiario
                    </a>
                </div>
                <div class="col text-end mt-2">
                    <!-- Input de búsqueda alineado a la derecha -->
                    <input type="text" id="search" class="form-control w-auto d-inline-block"
                        placeholder="Buscar beneficiario...">
                </div>
            </div>


            <!-- Tabla de beneficiarios -->
            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover mt-4">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Afiliado</th> <!-- Nueva columna -->
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $beneficiarios->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['cedula']; ?></td>
                            <td><?php echo $row['nombre_beneficiario']; ?></td>
                            <td><?php echo $row['apellido_beneficiario']; ?></td>
                            <td><?php echo $row['nombre_afiliado']; ?></td> <!-- Nombre del afiliado relacionado -->
                            <td class="text-center">
                                <!-- Botón Ver Información -->
                                <a href="#" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#vermodal" data-bs-cedula="<?= $row['cedula']; ?>">
                                    <i class="fas fa-eye"></i> Ver información
                                </a>
                                <button class="btn btn-sm btn-outline-info"
                                    onclick="verConsumoBeneficiario('<?= $row['cedula']; ?>', '<?= $row['nombre_beneficiario'].' '.$row['apellido_beneficiario']; ?>', '<?= $row['nombre_afiliado']; ?>')">
                                    <i class="fas fa-chart-pie"></i> Ver Plan
                                </button>
                                <!-- Botón Editar -->
                                <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editmodal" data-bs-cedula="<?= $row['cedula']; ?>">
                                    <i class="fas fa-edit"></i>Editar
                                </a>
                                <!-- Botón Eliminar -->
                                <a href="#" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#eliminamodal" data-bs-cedula="<?= $row['cedula']; ?>">
                                    <i class="fas fa-trash"></i> Eliminar
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                <a href="?page=<?php echo $i; ?>"
                    class="btn btn-sm <?php echo ($i == $currentPage) ? 'btn-secondary' : 'btn-primary'; ?> mx-1">
                    <?php echo $i; ?>
                </a>
                <?php } ?>
            </div>
        </div>
    </div>

</div>
<!-- Incluye los modales -->
<?php include 'C:/xampp/htdocs/IPSPUPTM/app/beneficiarios/modales/eliminar/eliminamodal.php'; ?>
<?php include 'C:/xampp/htdocs/IPSPUPTM/app/beneficiarios/modales/formulario/formulariomodal.php'; ?>
<?php include 'C:/xampp/htdocs/IPSPUPTM/app/beneficiarios/modales/actualizar/editmodal.php'; ?>
<?php include 'C:/xampp/htdocs/IPSPUPTM/app/beneficiarios/vermodal.php'; ?>
<?php include 'C:/xampp/htdocs/IPSPUPTM/app/beneficiarios/modales/modal_ver_consumo_benef.php'; ?>

<script src="/IPSPUPTM/assets/js/accionesbeneficiarios.js"></script>