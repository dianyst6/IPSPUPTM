<div class="cont-general" style="width: 100%; flex-grow: 1;"> <!-- Ajustando estilo inline para abarcar toda la sección -->

<?php
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php'; 
include 'C:/xampp/htdocs/IPSPUPTM/config/alertify.php'; 


$sql = "SELECT * FROM bitacora ORDER BY fecha DESC";
$result = $conn->query($sql);
echo "<div class='card shadow-lg'>"; // Añadido para mantener la consistencia con el estilo de las otras secciones
echo "<div class='card-body mt-5 m-3'> <!-- Cambiado a container-fluid para usar todo el espacio -->
        <h1 class='text-center mb-4'>Esta es la bitácora, se encarga de registrar todos los movimientos realizados en el sistema para mantener una documentación. Puedes descargar el registro de dichos movimientos en PDF</h1>
        <div class='d-flex justify-content-between mb-3'>
            <button id='deleteButton' class='btn btn-danger btn-sm'>Eliminar registros</button>
            <button id='downloadButton' class='btn btn-primary btn-sm'>Descargar</button>
        </div>";

if ($result->num_rows > 0) {
    echo "<div class='table-responsive'>
            <table class='table table-striped table-hover table-sm'>
                <thead class='table-dark text-center'>
                    <tr>
                        <th>Usuario</th>
                        <th>Acción</th>
                        <th>Descripción</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row['usuario'] . "</td>
                <td>" . $row['accion'] . "</td>
                <td>" . $row['descripcion'] . "</td>
                <td>" . $row['fecha'] . "</td>
            </tr>";
    }

    echo "</tbody>
            </table>
          </div>";
} else {
    echo "<p class='text-center'>No hay registros en la bitácora</p>";
}
echo "</div>";
echo "</div>"; // Cierre de la tarjeta

?>

</div>

<script src="/IPSPUPTM/assets/js/bitacora.js"></script>