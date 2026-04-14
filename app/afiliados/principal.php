<?php 
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';
include 'C:/xampp/htdocs/IPSPUPTM/config/alertify.php'; 


$rowsPerPage = 15; // Número de registros por página
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1; // Página actual
$offset = ($currentPage - 1) * $rowsPerPage;

// Consulta para obtener datos de afiliados junto con información de personas
$sqlAfiliados = "
    SELECT 
        p.cedula, 
        p.nombre, 
        p.apellido, 
        a.created_at, 
        a.updated_at 
    FROM afiliados a
    JOIN persona p ON a.cedula = p.cedula
";
$afiliados = $conn->query($sqlAfiliados);

$totalRowsResult = $conn->query ("SELECT COUNT(*) AS total FROM afiliados");
$totalRows = $totalRowsResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $rowsPerPage);

?>
<div class="card shadow-lg">
    <div class="mt-3 m-3 text-justify">

         <h1 class="fw-bold text-center" style="color: #062974;">Afiliados</h1>
            <hr class="mx-auto" style="width: 50px; height: 3px; background-color: #062974;">

        <!-- Contenedor para el botón y el input de búsqueda -->
        <div class="row mt-4 align-items-center">
            <div class="col-auto">
                <!-- Botón Agregar Afiliado alineado a la izquierda -->
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formulariomodal">
                    <i class="fas fa-plus-circle"></i> Agregar afiliado
                </a>
            </div>
            <div class="col text-end mt-2">
                <!-- Input de búsqueda alineado a la derecha -->
                <input type="text" id="search" class="form-control w-auto d-inline-block"
                    placeholder="Buscar afiliado...">
            </div>
        </div>

        <div id="alert-container"></div>


        <!-- Tabla de afiliados -->
        <div class="table-responsive">
            <table class="table table-sm table-striped table-hover mt-4">
                <thead class="table-dark">
                    <tr>
                        <th>Cédula</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $afiliados->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['cedula']; ?></td>
                        <td><?php echo $row['nombre']; ?></td>
                        <td><?php echo $row['apellido']; ?></td>
                        <td class="text-center">
                            <!-- Botón Ver Información -->
                            <a href="#" class="btn btn-primary-custom btn-sm" data-bs-toggle="modal" data-bs-target="#vermodal"
                                data-bs-cedula="<?= $row['cedula']; ?>">
                                <i class="fas fa-eye"></i> Ver información
                            </a>
                            <button class="btn btn-sm btn-primary text-white" title="Ver Consumo"
                                onclick="verResumenConsumo('<?php echo $row['cedula']; ?>', '<?php echo $row['nombre'].' '.$row['apellido']; ?>')">
                                <i class="fas fa-eye"></i> Ver Plan
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
        <div class="d-flex justify-content mt-3">
            <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
            <a href="?page=<?php echo $i; ?>"
                class="btn btn-sm <?php echo ($i == $currentPage) ? 'btn-secondary' : 'btn-primary'; ?> mx-1">
                <?php echo $i; ?>
            </a>
            <?php } ?>
        </div>

    </div>
</div>
<!-- Incluye los modales -->
<?php include 'C:/xampp/htdocs/IPSPUPTM/app/afiliados/modales/eliminar/eliminamodal.php'; ?>
<?php include 'C:/xampp/htdocs/IPSPUPTM/app/afiliados/modales/formulario/formulariomodal.php'; ?>
<?php include 'C:/xampp/htdocs/IPSPUPTM/app/afiliados/vermodal.php'; ?>
<?php include 'C:/xampp/htdocs/IPSPUPTM/app/afiliados/modales/actualizar/editmodal.php'; ?>
<?php include 'C:/xampp/htdocs/IPSPUPTM/app/afiliados/modales/modal_ver_consumo.php'; ?>


<script src="/IPSPUPTM/assets/js/accionesafiliados.js"></script>




</body>

</html>
