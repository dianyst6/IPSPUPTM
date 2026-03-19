<?php

include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';
include 'C:/xampp/htdocs/IPSPUPTM/config/alertify.php';


$rowsPerPage = 15;

$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

$offset = ($currentPage - 1) * $rowsPerPage;

// 1. CAMBIO: Consulta enfocada en la tabla comunidad_uptm
$sqlComunidad = "
    SELECT 
        cedula, 
        nombre, 
        apellido 
    FROM comunidad_uptm 
    LIMIT $offset, $rowsPerPage
";
$comunidad = $conn->query($sqlComunidad);

// 2. CAMBIO: Conteo total de registros de la tabla correcta
$totalRowsResult = $conn->query("SELECT COUNT(*) AS total FROM comunidad_uptm");
$totalRows = $totalRowsResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $rowsPerPage);
?>

<div class="card shadow-lg">
    <div class="mt-3 m-3 text-justify">
        <h1 class="fw-bold text-center" style="color: #062974;">Comunidad UPTM (Externos)</h1>
        <hr class="mx-auto" style="width: 50px; height: 3px; background-color: #062974;">

        <div class="row mt-4 align-items-center">
            <div class="col-auto">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formulariomodal">
                    <i class="fas fa-plus-circle"></i> Agregar a Comunidad
                </a>
            </div>
            <div class="col text-end mt-2">
                <input type="text" id="search" class="form-control w-auto d-inline-block"
                    placeholder="Buscar por cédula o nombre...">
            </div>
        </div>

        <div id="alert-container"></div>

        <div class="table-responsive">
            <table class="table table-sm table-striped table-hover mt-4">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Cédula</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-comunidad">
                    <?php while ($row = $comunidad->fetch_assoc()) { ?>
                    <tr>
                        <td class="text-center"><?php echo $row['cedula']; ?></td>
                        <td><?php echo $row['nombre']; ?></td>
                        <td><?php echo $row['apellido']; ?></td>
                        <td class="text-center">
                            <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editmodal" data-bs-cedula="<?= $row['cedula']; ?>">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                        </td>
                    </tr>
                    <?php
}?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
            <a href="?page=<?php echo $i; ?>"
                class="btn btn-sm <?php echo($i == $currentPage) ? 'btn-secondary' : 'btn-primary'; ?> mx-1">
                <?php echo $i; ?>
            </a>
            <?php
}?>
        </div>
    </div>
</div>

<?php include 'C:/xampp/htdocs/IPSPUPTM/app/comunidaduptm/eliminar/eliminamodal.php'; ?>
<?php include 'C:/xampp/htdocs/IPSPUPTM/app/comunidaduptm/formulario/formulariomodal.php'; ?>
<?php include 'C:/xampp/htdocs/IPSPUPTM/app/comunidaduptm/actualizar/editmodal.php'; ?>
<script src="/IPSPUPTM/assets/js/accionescomunidaduptm.js"></script>
