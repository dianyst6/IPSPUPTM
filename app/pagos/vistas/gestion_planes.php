<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold text-center" style="color: #062974;">Gestionar Planes de Salud</h1>
            <hr class="mx-auto" style="width: 50px; height: 3px; background-color: #062974;">
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="/IPSPUPTM/home.php?vista=agregarplan" class="btn btn-lg text-white shadow-sm" style="background-color: #062974;">
            <i class="fa-solid fa-plus me-2"></i>Crear Plan Salud
        </a>
        
        <a href="/IPSPUPTM/home.php?vista=gestionplanesasignados" class="btn btn-lg text-white shadow-sm" style="background-color: #062974;">
            <i class="fa-solid fa-book-medical me-2"></i>Gestionar Planes Asignados
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow border-0">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h4 class="fw-bold mb-0 text-secondary">
                        <i class="fa-solid fa-list-check me-2"></i>Planes Registrados en el Sistema
                    </h4>
                </div>

                <div class="card-body p-4">
                    <div class="row"> <?php
                        include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';
                        $sql_planes = "SELECT * FROM planes ORDER BY ID_planes DESC";
                        $res_planes = mysqli_query($conn, $sql_planes);

                        if (mysqli_num_rows($res_planes) > 0) {
                            while ($plan = mysqli_fetch_assoc($res_planes)) {
                                $id_plan = $plan['ID_planes'];
                                
                                // Consultar componentes
                                $sql_comp = "SELECT e.nombre_examen, cp.cantidad_maxima 
                                             FROM componentes_planes cp
                                             INNER JOIN examenes e ON cp.ID_examen_componentes = e.ID_examen
                                             WHERE cp.ID_planes_componentes = '$id_plan'";
                                $res_comp = mysqli_query($conn, $sql_comp);
                        ?>
                                <div class="col-md-6 mb-4">
                                    <div class="p-3 h-100 border-start border-4 rounded shadow-sm bg-white" style="border-color: #062974 !important;">
                                        <div class="d-flex align-items-start justify-content-between mb-2">
                                            <div class="ms-2">
                                                <h5 class="mb-0 fw-bold" style="color: #062974;"><?php echo $plan['nombre_plan']; ?></h5>
                                                <small class="text-muted d-block mt-1"><?php echo $plan['descripcion']; ?></small>
                                            </div>
                                            <div class="btn-group border rounded bg-light" role="group">
                                                <a href="#" class="btn btn-sm btn-outline-primary border-0" title="Ver"><i class="fa-solid fa-eye"></i></a>
                                                <a href="#" class="btn btn-sm btn-outline-warning border-0" title="Editar"><i class="fa-solid fa-pen"></i></a>
                                                <a href="#" class="btn btn-sm btn-outline-danger border-0" title="Eliminar"><i class="fa-solid fa-trash"></i></a>
                                            </div>
                                        </div>

                                        <div class="ms-2 mt-3">
                                            <h6 class="fw-bold small text-uppercase text-secondary" style="font-size: 0.75rem;">Cobertura:</h6>
                                            <ul class="list-unstyled mb-3">
                                                <?php 
                                                if (mysqli_num_rows($res_comp) > 0) {
                                                    while ($comp = mysqli_fetch_assoc($res_comp)) {
                                                        echo "<li class='small mb-1'><i class='fa-solid fa-check-circle text-success me-2'></i>";
                                                        echo $comp['nombre_examen'] . " <span class='badge bg-light text-dark border'>Máx: " . $comp['cantidad_maxima'] . "</span>";
                                                        echo "</li>";
                                                    }
                                                } else {
                                                    echo "<li class='small text-muted italic'>Sin exámenes asociados.</li>";
                                                }
                                                ?>
                                            </ul>
                                        </div>

                                        <div class="d-flex justify-content-end align-items-center mt-auto pt-2 border-top">
                                            <span class="h5 mb-0 fw-bold" style="color: #062974;">
                                                $<?php echo number_format($plan['precio'], 2); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        } else {
                            echo "<div class='col-12 text-center py-5 text-muted'>No hay planes registrados actualmente.</div>";
                        }
                        ?>
                    </div> <div class="d-flex justify-content-end align-items-center mt-4 p-3 bg-light rounded border">
                        <h6 class="mb-0 fw-bold" style="color: #062974;">
                            Total Planes Activos: <span class="badge bg-primary ms-2"><?php echo mysqli_num_rows($res_planes); ?></span>
                        </h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



