<?php
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre_plan'];
    $precio = $_POST['precio'];
    $desc = $_POST['descripcion'];

    // 1. Insertar el Plan principal
    $sql_plan = "INSERT INTO planes (nombre_plan, precio, descripcion) VALUES ('$nombre', '$precio', '$desc')";
    
    if (mysqli_query($conn, $sql_plan)) {
        $id_plan_nuevo = mysqli_insert_id($conn); // Obtenemos el ID del plan que se acaba de crear

        // 2. Procesar los componentes enviados en los arrays []
        $examenes = $_POST['id_examen'];
        $cantidades = $_POST['cantidad'];

        for ($i = 0; $i < count($examenes); $i++) {
            $id_ex = $examenes[$i];
            $cant = $cantidades[$i];

            if (!empty($id_ex)) {
                $sql_comp = "INSERT INTO componentes_planes (ID_planes_componentes, ID_examen_componentes, cantidad_maxima) 
                             VALUES ('$id_plan_nuevo', '$id_ex', '$cant')";
                mysqli_query($conn, $sql_comp); // Insertamos cada relación
            }
        }
        
        echo "<script>alert('Plan y componentes registrados con éxito'); window.location.href='/IPSPUPTM/home.php?vista=gestionplanes';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>