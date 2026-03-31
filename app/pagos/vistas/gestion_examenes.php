<div class="card shadow-lg">
    <div class="card-body">
        <h1 class="fw-bold text-center" style="color: #062974;">Gestión de Exámenes y Precios</h1>
        <hr class="mx-auto" style="width: 50px; height: 3px; background-color: #062974;">
        
        <div class="table-responsive mt-4">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Examen</th>
                        <th>Especialidad</th>
                        <th>Precio ($)</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';
                    $sql = "SELECT e.*, esp.nombre_especialidad 
                            FROM examenes e 
                            INNER JOIN especialidades esp ON e.ID_especialidad_examenes = esp.id_especialidad 
                            ORDER BY esp.nombre_especialidad ASC";
                    $res = mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_assoc($res)):
                    ?>
                    <tr>
                        <td><?php echo $row['nombre_examen']; ?></td>
                        <td><?php echo $row['nombre_especialidad']; ?></td>
                        <td>
                            <form action="/IPSPUPTM/app/pagos/vistas/actualizar_examen.php" method="POST" class="d-flex align-items-center">
                                <input type="hidden" name="id_examen" value="<?php echo $row['ID_examen']; ?>">
                                <input type="number" step="0.01" name="precio" class="form-control form-control-sm me-2" 
                                       style="width: 100px;" value="<?php echo $row['precio']; ?>" required>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i></button>
                            </form>
                        </td>
                        <td>
                            <button class="btn btn-warning btn-sm" title="Editar Nombre"><i class="fas fa-edit"></i></button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
