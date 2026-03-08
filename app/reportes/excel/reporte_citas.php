<?php
require_once 'C:/xampp/htdocs/IPSPUPTM/vendor/autoload.php';
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

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

// Crear un nuevo Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// --- Insertar Logos ---
// Logo IPSPUPTM en la esquina superior izquierda
$drawing = new Drawing();
$drawing->setName('Logo IPSPUPTM');
$drawing->setDescription('Logo del Instituto de Previsión Social de los Profesores de la Universidad Politécnica Territorial del Estado Mérida Kléber Ramirez');
$drawing->setPath('C:/xampp/htdocs/IPSPUPTM/recursos/img/IPSPUPTMlogo.png'); // Ruta del logo
$drawing->setHeight(50); // Tamaño del logo ajustado a 50
$drawing->setCoordinates('A1');
$drawing->setWorksheet($sheet);

// Logo UPTM en la esquina superior derecha
$drawing2 = new Drawing();
$drawing2->setName('Logo UPTM');
$drawing2->setDescription('Logo de la Universidad Politécnica Territorial del Estado Mérida Kléber Ramirez');
$drawing2->setPath('C:/xampp/htdocs/IPSPUPTM/recursos/img/UPTM_logo.png'); // Ruta del logo
$drawing2->setHeight(50); // Tamaño del logo ajustado a 50
$drawing2->setCoordinates('H1'); // Ajustar según el número de columnas
$drawing2->setWorksheet($sheet);

// --- Título y Subtítulo ---
$titulo_completo = 'Instituto de Previsión Social de los Profesores de la UPTM';
$sheet->setCellValue('A3', $titulo_completo);
$sheet->setCellValue('A4', $titulo); // Usar el título dinámico
$sheet->mergeCells('A3:H3'); // Ajustar el rango de celdas para el título
$sheet->mergeCells('A4:H4'); // Ajustar el rango de celdas para el subtítulo

$sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A3')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A4')->getFont()->setBold(true)->setSize(12);

// Calcular el ancho necesario para el título
$ancho_titulo = strlen($titulo_completo) * 1.2;  // Ajustar el factor multiplicativo según sea necesario
$sheet->getColumnDimension('A')->setWidth($ancho_titulo);

// --- Fecha de Emisión ---
$fecha_emision = date('d-m-Y');
$sheet->setCellValue('A5', 'Emitido: ' . $fecha_emision);
$sheet->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A5')->getFont()->setSize(10);

// --- Encabezados de la tabla ---
$headerRow = ['Fecha', 'Paciente', 'Cédula', 'Tipo', 'Especialidad', 'Descripción'];
$sheet->fromArray($headerRow, null, 'A7'); // Colocar los encabezados en la fila 7

$headerStyle = [
    'font' => ['bold' => true],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['argb' => 'FFFFFF00'], // Amarillo
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'], // Negro
        ],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER, // Centrar contenido
        'vertical' => Alignment::VERTICAL_CENTER, // Centrar verticalmente (opcional)
        'wrapText' => true, // Ajustar texto a la celda (opcional)
    ],
];
$sheet->getStyle('A7:F7')->applyFromArray($headerStyle); // Aplica el estilo a la fila de encabezados (A7 a F7)

// --- Datos de la tabla ---
$fila = 8; // Comenzar en la fila 8 para los datos
while ($row = $resultado->fetch_assoc()) {
    $sheet->setCellValue('A' . $fila, date('d-m-Y', strtotime($row['fecha_cita'])));
    $sheet->setCellValue('B' . $fila, $row['nombre_paciente']);
    $sheet->setCellValue('C' . $fila, $row['cedula_paciente']);
    $sheet->setCellValue('D' . $fila, $row['tipo_paciente']);
    $sheet->setCellValue('E' . $fila, $row['nombre_especialidad']);
    $sheet->setCellValue('F' . $fila, $row['descripcion']);
    $fila++;
}

// Aplicar bordes a los datos
$dataStyle = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'], // Negro
        ],
    ],
];
$sheet->getStyle('A8:F' . ($fila - 1))->applyFromArray($dataStyle); // Aplica bordes a los datos (desde A8 hasta la última fila)

// Ajustar automáticamente el ancho de las columnas
for ($col = 'A'; $col <= 'F'; $col++) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Crear el archivo Excel y forzar la descarga
$writer = new Xlsx($spreadsheet);
$filename = 'reporte_citas_' . $tipo_reporte . '_' . date('Ymd') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
?>
