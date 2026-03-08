<?php  
require_once 'C:/xampp/htdocs/IPSPUPTM/vendor/autoload.php'; // Autoload de Composer  
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';  

use PhpOffice\PhpWord\PhpWord;  
use PhpOffice\PhpWord\IOFactory;  

// Obtener el tipo de reporte desde un parámetro GET  
$tipo_reporte = $_GET['tipo_reporte'] ?? 'semanal'; // 'semanal' por defecto  

$titulo_base = "Reporte de Citas";  
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

$query_citas = "  
    SELECT  
        c.id_cita,  
        c.fecha_cita,  
        c.descripcion,  
        e.nombre_especialidad,  
        CASE  
            WHEN ca.idcita IS NOT NULL THEN 'Afiliado'  
            WHEN cb.idcita IS NOT NULL THEN 'Beneficiario'  
        END AS tipo_paciente,  
        CASE  
            WHEN ca.idcita IS NOT NULL THEN CONCAT(p_a.nombre,' ',p_a.apellido)  
            WHEN cb.idcita IS NOT NULL THEN CONCAT(p_b.nombre,' ',p_b.apellido)  
        END AS nombre_paciente,  
        CASE  
            WHEN ca.idcita IS NOT NULL THEN p_a.cedula  
            WHEN cb.idcita IS NOT NULL THEN p_b.cedula  
        END AS cedula_paciente  
    FROM citas c  
    LEFT JOIN citas_afil ca ON c.id_cita = ca.idcita  
    LEFT JOIN afiliados a ON ca.id_afiliado = a.id  
    LEFT JOIN persona p_a ON a.cedula = p_a.cedula  
    LEFT JOIN citas_benef cb ON c.id_cita = cb.idcita  
    LEFT JOIN beneficiarios b ON cb.id_beneficiario = b.id  
    LEFT JOIN persona p_b ON b.cedula = p_b.cedula  
    LEFT JOIN especialidades e ON c.id_especialidad = e.id_especialidad  
    WHERE c.fecha_cita >= DATE_SUB(CURDATE(), $interval)  
    ORDER BY c.fecha_cita DESC  
";  

$resultado = $conn->query($query_citas);  

if (!$resultado) {  
    echo "Error en la consulta de citas: " . $conn->error;  
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
$table->addCell(2000)->addText('Fecha', ['bold' => true]);
$table->addCell(3000)->addText('Paciente', ['bold' => true]);
$table->addCell(2000)->addText('Cédula', ['bold' => true]);
$table->addCell(2000)->addText('Tipo', ['bold' => true]);
$table->addCell(3000)->addText('Especialidad', ['bold' => true]);
$table->addCell(5000)->addText('Descripción', ['bold' => true]);

// Agregar filas con datos
while ($row = $resultado->fetch_assoc()) {
    $table->addRow();
    $table->addCell(2000)->addText(date('d-m-Y', strtotime($row['fecha_cita'])));
    $table->addCell(3000)->addText($row['nombre_paciente']);
    $table->addCell(2000)->addText($row['cedula_paciente']);
    $table->addCell(2000)->addText($row['tipo_paciente']);
    $table->addCell(3000)->addText($row['nombre_especialidad']);
    $table->addCell(5000)->addText($row['descripcion']);
}

// Guardar el archivo Word
$filename = 'reporte_citas_' . $tipo_reporte . '_' . date('Ymd') . '.docx';
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment; filename="' . $filename . '"');
$phpWord->save('php://output');
?>
