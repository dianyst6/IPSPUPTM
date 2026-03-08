<?php  
require_once 'C:/xampp/htdocs/IPSPUPTM/vendor/autoload.php'; // Autoload de Composer  
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';  

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

// Parámetros: se espera recibir el tipo de reporte vía GET  
$tipo_reporte = isset($_GET['tipo_reporte']) && in_array($_GET['tipo_reporte'], ['semanal','quincenal','mensual']) ? $_GET['tipo_reporte'] : 'semanal';  
$titulo_base = "Reporte de Especialidades Más Solicitadas";  
$interval = '';  
$titulo = '';  

// Adaptar la consulta y el título según el tipo de reporte  
switch ($tipo_reporte) {  
    case 'semanal':  
        $interval = 'INTERVAL 1 WEEK';  
        $titulo = $titulo_base . " - Semanal";  
        break;  
    case 'quincenal':  
        $interval = 'INTERVAL 2 WEEK';  
        $titulo = $titulo_base . " - Quincenal";  
        break;  
    case 'mensual':  
        $interval = 'INTERVAL 1 MONTH';  
        $titulo = $titulo_base . " - Mensual";  
        break;  
    default:  
        echo "Tipo de reporte inválido.";  
        exit;  
}  

$query = "  
    SELECT  
        e.nombre_especialidad,  
        COUNT(c.id_especialidad) AS total_solicitudes  
    FROM especialidades e  
    JOIN citas c ON e.id_especialidad = c.id_especialidad  
    WHERE c.fecha_cita >= DATE_SUB(CURDATE(), $interval)  
    GROUP BY e.nombre_especialidad  
    ORDER BY total_solicitudes DESC  
";  

$resultado = $conn->query($query);  

if (!$resultado) {  
    echo "Error en la consulta de especialidades: " . $conn->error;  
    exit;  
}  

// Crear el documento Word
$phpWord = new PhpWord();
$section = $phpWord->addSection();

// Agregar título y subtítulo
$section->addText('Instituto de Previsión Social de los profesores de la Universidad Politécnica Territorial del Estado Mérida Kléber Ramirez', ['bold' => true, 'size' => 14], ['alignment' => 'center']);
$section->addText($titulo, ['bold' => true, 'size' => 12], ['alignment' => 'center']);
$section->addTextBreak(2); // Espacio en blanco

// Crear tabla con encabezados
$table = $section->addTable(['borderSize' => 6, 'borderColor' => '999999', 'cellMargin' => 50]);
$table->addRow();
$table->addCell(4000)->addText('Especialidad', ['bold' => true]);
$table->addCell(2000)->addText('Total Solicitudes', ['bold' => true]);

// Agregar filas de datos  
while ($row = $resultado->fetch_assoc()) {  
    $table->addRow();
    $table->addCell(4000)->addText($row['nombre_especialidad']);
    $table->addCell(2000)->addText($row['total_solicitudes']);
}

// Guardar y descargar el archivo Word
$filename = 'reporte_especialidades_' . $tipo_reporte . '_' . date('Ymd') . '.docx';
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment; filename="' . $filename . '"');
$phpWord->save('php://output');
?>
