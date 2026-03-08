<?php
require_once 'C:/xampp/htdocs/IPSPUPTM/vendor/autoload.php'; // Incluye el autoloader de Composer
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

use Dompdf\Dompdf;

// Parámetros: se espera recibir el tipo de reporte vía GET
$tipo_reporte = isset($_GET['tipo_reporte']) && in_array($_GET['tipo_reporte'], ['semanal','quincenal','mensual']) ? $_GET['tipo_reporte'] : 'semanal';
$titulo_base = "Reporte de Especialidades Más Solicitadas";
$interval = '';
$titulo = '';


// Ruta de los logos
$logo_ipspuptm_path = 'C:/xampp/htdocs/IPSPUPTM/recursos/img/IPSPUPTMlogo.png';
$logo_uptm_path = 'C:/xampp/htdocs/IPSPUPTM/recursos/img/UPTM_logo.png';

// Función para convertir la imagen a Base64
function imageToBase64($path) {
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    return 'data:image/' . $type . ';base64,' . base64_encode($data);
}

// Convertir las imágenes a Base64
$logo_ipspuptm_base64 = imageToBase64($logo_ipspuptm_path);
$logo_uptm_base64 = imageToBase64($logo_uptm_path);

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

// Consulta: se usa la tabla de citas (c.fecha_cita) para filtrar por período y se agrupa la cantidad de solicitudes por especialidad.
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

$html = '<!DOCTYPE html><html><head><meta charset="utf-8"><title>' . htmlspecialchars($titulo) . '</title><style>';
$html .= 'body { font-family: Arial, sans-serif; font-size: 8pt; }';
$html .= '.header { position: relative; text-align: center; margin-bottom: 10px; }';
$html .= '.logo-left { position: absolute; top: 0; left: 0; height: 60px; } /* Ajusta la altura según necesites */';
$html .= '.logo-right { position: absolute; top: 0; right: 0; height: 60px; } /* Ajusta la altura según necesites */';
$html .= '.title-container { margin: 0 auto; } /* Centra el título */';
$html .= '.table { width: 100%; border-collapse: collapse; margin-top: 20px; }';
$html .= '.table th, .table td { border: 1px solid #000; padding: 5px; vertical-align: top; font-size: 7pt; } /* Reduje un poco la fuente para tablas grandes */';
$html .= '.table th { background-color: #f0f0f0; font-weight: bold; }';
$html .= '</style></head><body>';
$html .= '<div class="header">';
$html .= '<img src="' . $logo_ipspuptm_base64 . '" class="logo-left">';
$html .= '<div class="title-container">';
$html .= '<h2>Instituto de Previsión Social de los Profesores de la <br>
Universidad Politécnica Territorial Kléber Ramirez del <br> 
Estado Mérida</h2>';
$html .= '<h3>' . htmlspecialchars($titulo) . '</h3>';
$html .= '</div>';
$html .= '<img src="' . $logo_uptm_base64 . '" class="logo-right">';
$html .= '</div>';
$html .= '<table class="table">';
$html .= '<thead><tr>';
$headers = ['Especialidad', 'Total Solicitudes'];
foreach ($headers as $header) {
    $html .= '<th>' . htmlspecialchars($header) . '</th>';
}
$html .= '</tr></thead><tbody>';

while ($row = $resultado->fetch_assoc()) {
    $html .= '<tr>';
    $html .= '<td>' . htmlspecialchars($row['nombre_especialidad']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['total_solicitudes']) . '</td>';
    $html .= '</tr>';
}

$fecha_emision = date('d-m-Y'); // Obtiene la fecha y hora actual

$html .= '</tbody></table>';
$html .= '<div style="text-align: center; font-size: 9pt; color: #777; margin-top: 20px;">';
$html .= 'Fecha de Emisión: ' . htmlspecialchars($fecha_emision);
$html .= '</div>';
$html .= '</body></html>';


// Instanciar Dompdf
$dompdf = new Dompdf(['isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);

// Cargar el HTML
$dompdf->loadHtml($html, 'UTF-8');

// (Opcional) Establecer el tamaño y la orientación del papel
$dompdf->setPaper('Letter', 'portrait');

// Renderizar el HTML a PDF
$dompdf->render();

// Enviar el PDF generado al navegador para su descarga
$filename = 'reporte_especialidades_' . $tipo_reporte . '_' . date('Ymd') . '.pdf';
$dompdf->stream($filename, ['Attachment' => 1]);
?>