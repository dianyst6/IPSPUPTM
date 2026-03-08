<?php
require_once 'C:/xampp/htdocs/IPSPUPTM/vendor/autoload.php'; // Incluye el autoloader de Composer
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

use Dompdf\Dompdf;

$titulo = "Reporte de Afiliados";

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
// Consulta para obtener todos los afiliados
$sql = "SELECT
            p.cedula,
            p.nombre,
            p.apellido,
            p.fechanacimiento,
            p.genero,
            p.telefono,
            p.correo,
            p.ocupacion,
            a.created_at,
            a.updated_at
        FROM
            afiliados a
        JOIN
            persona p ON a.cedula = p.cedula";

$resultado = $conn->query($sql);

if (!$resultado) {
    echo "Error en la consulta de afiliados: " . $conn->error;
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
$html .= '<h2>Instituto de Previsión Social de los Profesores de la Universidad <br>
Politécnica Territorial Kléber Ramirez del Estado Mérida</h2>';
$html .= '<h3>' . htmlspecialchars($titulo) . '</h3>';
$html .= '</div>';
$html .= '<img src="' . $logo_uptm_base64 . '" class="logo-right">';
$html .= '</div>';
$html .= '<table class="table">';
$html .= '<thead><tr>';
$headers = ['Cédula', 'Nombre', 'Apellido', 'F. Nacimiento', 'Género', 'Teléfono', 'Correo', 'Ocupación', 'Fecha Registro', 'Última Actualización'];
foreach ($headers as $header) {
    $html .= '<th>' . htmlspecialchars($header) . '</th>';
}
$html .= '</tr></thead><tbody>';

while ($row = $resultado->fetch_assoc()) {
    $html .= '<tr>';
    $html .= '<td>' . htmlspecialchars($row['cedula']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['nombre']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['apellido']) . '</td>';
    $html .= '<td>' . htmlspecialchars(date('d-m-Y', strtotime($row['fechanacimiento']))) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['genero']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['telefono']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['correo']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['ocupacion']) . '</td>';
    $html .= '<td>' . htmlspecialchars(date('d-m-Y H:i:s', strtotime($row['created_at']))) . '</td>';
    $html .= '<td>' . htmlspecialchars(date('d-m-Y H:i:s', strtotime($row['updated_at']))) . '</td>';
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
$dompdf->setPaper('Letter', 'landscape'); // Podría ser landscape para más columnas

// Renderizar el HTML a PDF
$dompdf->render();

// Enviar el PDF generado al navegador para su descarga
$filename = 'reporte_afiliados_' . date('Ymd') . '.pdf';
$dompdf->stream($filename, ['Attachment' => 1]);
?>