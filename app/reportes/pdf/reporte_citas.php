<?php
require_once 'C:/xampp/htdocs/IPSPUPTM/vendor/autoload.php'; // Incluye el autoloader de Composer
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

use Dompdf\Dompdf;

// Obtener el tipo de reporte desde un parámetro GET
$tipo_reporte = $_GET['tipo_reporte'] ?? 'semanal'; // 'semanal' por defecto

$titulo_base = "Reporte de Citas";
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
$headers = ['Fecha', 'Paciente', 'Cédula', 'Tipo', 'Especialidad', 'Descripción'];
foreach ($headers as $header) {
    $html .= '<th>' . htmlspecialchars($header) . '</th>';
}
$html .= '</tr></thead><tbody>';

while ($row = $resultado->fetch_assoc()) {
    $html .= '<tr>';
    $html .= '<td>' . htmlspecialchars(date('d-m-Y', strtotime($row['fecha_cita']))) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['nombre_paciente']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['cedula_paciente']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['tipo_paciente']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['nombre_especialidad']) . '</td>';
    $html .= '<td style="width: 50mm;">' . htmlspecialchars($row['descripcion']) . '</td>';
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
$filename = 'reporte_citas_' . $tipo_reporte . '_' . date('Ymd') . '.pdf';
$dompdf->stream($filename, ['Attachment' => 1]);
?>