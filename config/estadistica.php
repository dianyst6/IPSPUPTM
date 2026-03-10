<?php
// Incluir el archivo de conexión
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

// Inicializar las variables
$total_afiliados = 0;
$total_beneficiarios = 0;
$total_citas = 0;
$total_citas_uptm = 0;

// Inicializar variables para las especialidades
$citas_ginecologia = 0;
$citas_medicina_interna = 0;
$citas_odontologia = 0;
$citas_oftalmologia = 0;
$citas_gastroenterologia = 0;
$citas_imagenologia = 0;

// Consultar el total de afiliados
$sql_afiliados = "SELECT COUNT(*) AS total_afiliados FROM afiliados";
$result_afiliados = $conn->query($sql_afiliados);
if ($result_afiliados->num_rows > 0) {
    $row = $result_afiliados->fetch_assoc();
    $total_afiliados = $row['total_afiliados'];
}

// Consultar el total de beneficiarios
$sql_beneficiarios = "SELECT COUNT(*) AS total_beneficiarios FROM beneficiarios";
$result_beneficiarios = $conn->query($sql_beneficiarios);
if ($result_beneficiarios->num_rows > 0) {
    $row = $result_beneficiarios->fetch_assoc();
    $total_beneficiarios = $row['total_beneficiarios'];
}

// Consultar el total de citas
$sql_citas = "SELECT COUNT(*) AS total_citas FROM citas";
$result_citas = $conn->query($sql_citas);
if ($result_citas->num_rows > 0) {
    $row = $result_citas->fetch_assoc();
    $total_citas = $row['total_citas'];
}


// Consultar el total de citas_uptm
$sql_citas_uptm = "SELECT COUNT(*) AS total_citas_uptm FROM citas_uptm";
$result_citas_uptm = $conn->query($sql_citas_uptm);
if ($result_citas_uptm->num_rows > 0) {
    $row = $result_citas_uptm->fetch_assoc();
    $total_citas_uptm = $row['total_citas_uptm'];
}

// Consultar citas por especialidad
$sql_especialidades = "
    SELECT e.nombre_especialidad, COUNT(c.id_cita) as total 
    FROM citas c 
    INNER JOIN especialidades e ON c.id_especialidad = e.id_especialidad 
    GROUP BY e.id_especialidad
";
$result_especialidades = $conn->query($sql_especialidades);

if ($result_especialidades && $result_especialidades->num_rows > 0) {
    while($row = $result_especialidades->fetch_assoc()) {
        $nombre = mb_strtolower($row['nombre_especialidad'], 'UTF-8');
        $total = $row['total'];
        
        // Asignar los totales a su respectiva variable según el nombre
        if (strpos($nombre, 'ginecolog') !== false) {
            $citas_ginecologia = $total;
        } elseif (strpos($nombre, 'medicina') !== false) {
            $citas_medicina_interna = $total;
        } elseif (strpos($nombre, 'odontolog') !== false) {
            $citas_odontologia = $total;
        } elseif (strpos($nombre, 'oftamolog') !== false || strpos($nombre, 'oftalmolog') !== false) {
            $citas_oftalmologia = $total;
        } elseif (strpos($nombre, 'gastroenterolog') !== false) {
            $citas_gastroenterologia = $total;
        } elseif (strpos($nombre, 'imagenolog') !== false) {
            $citas_imagenologia = $total;
        }
    }
}

// Cerrar la conexión a la base de datos
$conn->close();
?>