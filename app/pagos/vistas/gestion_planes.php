<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold text-center" style="color: #062974;">Gestionar planes salud</h1>
            <hr class="mx-auto" style="width: 50px; height: 3px; background-color: #062974;">
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-5">
            <div class="card text-center text-white shadow mb-4 border-0"
                style="background-color: #062974; min-height: 180px;">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="fa-solid fa-plus fa-3x mb-2"></i>
                    <h4 class="card-title"><strong>Crear de plan Salud</strong></h4>
                    <a href="/IPSPUPTM/home.php?vista=agregarplan" class="stretched-link"></a>
                </div>
            </div>


            <div class="card text-center text-white shadow border-0"
                style="background-color: #062974; min-height: 180px;">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="fa-solid fa-book-medical fa-3x mb-2"></i>
                    <h4 class="card-title"><strong>Gestionar planes asignados</strong></h4>
                    <a href="/IPSPUPTM/home.php?vista=gestionplanesasignados" class="stretched-link"></a>
                </div>
            </div>
        </div>


        <div class="col-md-7">
            <div class="card shadow border-0 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h4 class="fw-bold mb-0"><i class="fa-solid fa-list-check me-2"></i>Visualizar planes de pagos</h4>
                </div>

                <div class="card-body p-4">
                    <?php
                    include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';
    // 1. Consultar todos los planes registrados
    $sql_planes = "SELECT * FROM planes ORDER BY ID_planes DESC";
    $res_planes = mysqli_query($conn, $sql_planes);

    if (mysqli_num_rows($res_planes) > 0) {
        while ($plan = mysqli_fetch_assoc($res_planes)) {
            $id_plan = $plan['ID_planes'];
            
            // 2. Consultar los componentes (exámenes) asociados a este plan específico
            $sql_comp = "SELECT e.nombre_examen, cp.cantidad_maxima 
                         FROM componentes_planes cp
                         INNER JOIN examenes e ON cp.ID_examen_componentes = e.ID_examen
                         WHERE cp.ID_planes_componentes = '$id_plan'";
            $res_comp = mysqli_query($conn, $sql_comp);
            ?>

                    <div class="p-3 mb-4 border-start border-4 rounded shadow-sm bg-white"
                        style="border-color: #062974 !important;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="ms-2">
                                <h5 class="mb-0 fw-bold" style="color: #062974;"><?php echo $plan['nombre_plan']; ?>
                                </h5>
                                <small class="text-muted"><?php echo $plan['descripcion']; ?></small>
                            </div>
                            <div class="btn-group border rounded" role="group">
                                <a href="#" class="btn btn-sm btn-outline-primary border-0" title="Visualizar"><i
                                        class="fa-solid fa-eye"></i></a>
                                <a href="#" class="btn btn-sm btn-outline-warning border-0" title="Editar"><i
                                        class="fa-solid fa-pen"></i></a>
                                <a href="#" class="btn btn-sm btn-outline-danger border-0" title="Eliminar"><i
                                        class="fa-solid fa-trash"></i></a>
                            </div>
                        </div>

                        <div class="ms-2 mt-2">
                            <h6 class="fw-bold small text-uppercase text-secondary">Cobertura del Plan:</h6>
                            <ul class="list-unstyled mb-0">
                                <?php 
                        if (mysqli_num_rows($res_comp) > 0) {
                            while ($comp = mysqli_fetch_assoc($res_comp)) {
                                echo "<li class='small'><i class='fa-solid fa-check-circle text-success me-2'></i>";
                                echo $comp['nombre_examen'] . " (Máx: " . $comp['cantidad_maxima'] . ")";
                                echo "</li>";
                            }
                        } else {
                            echo "<li class='small text-muted italic'>Este plan no tiene exámenes asociados.</li>";
                        }
                        ?>
                            </ul>
                        </div>

                        <div class="text-end mt-2">
                            <span class="badge rounded-pill" style="background-color: #062974;">Precio:
                                $<?php echo number_format($plan['precio'], 2); ?></span>
                        </div>
                    </div>

                    <?php
        }
    } else {
        echo "<div class='text-center py-4 text-muted'>No hay planes registrados actualmente.</div>";
    }
    ?>

                    <div class="d-flex justify-content-between align-items-center mt-4 p-4 bg-light rounded border-0">
                        <h5 class="mb-0 text-secondary">Estado del Sistema:</h5>
                        <h6 class="mb-0 fw-bold" style="color: #062974;">Planes Activos:
                            <?php echo mysqli_num_rows($res_planes); ?></h6>
                    </div>

                </div>
            </div>
        </div>
    </div>





    <script src="/IPSPUPTM/assets/js/accionescitas.js"></script>