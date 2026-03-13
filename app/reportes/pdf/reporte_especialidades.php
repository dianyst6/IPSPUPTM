<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once 'C:/xampp/htdocs/IPSPUPTM/assets/fpdf.php';
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

$tipo_reporte = isset($_GET['tipo_reporte']) && in_array($_GET['tipo_reporte'], ['semanal','quincenal','mensual'])
                ? $_GET['tipo_reporte'] : 'semanal';

$titulo_base = "Reporte de Especialidades Más Solicitadas";
switch ($tipo_reporte) {
    case 'semanal':   $interval = 'INTERVAL 1 WEEK';  $titulo = "$titulo_base - Semanal";   break;
    case 'quincenal': $interval = 'INTERVAL 2 WEEK';  $titulo = "$titulo_base - Quincenal"; break;
    case 'mensual':   $interval = 'INTERVAL 1 MONTH'; $titulo = "$titulo_base - Mensual";   break;
}

class PDF_Especialidades extends FPDF {
    protected $titulo;

    function __construct($titulo) {
        parent::__construct('P', 'mm', 'Letter');
        $this->titulo = $titulo;
    }

    function Header() {
        $logo_ipsp = 'C:/xampp/htdocs/IPSPUPTM/recursos/img/IPSPUPTMlogo.png';
        $logo_uptm = 'C:/xampp/htdocs/IPSPUPTM/recursos/img/UPTM_logo.png';
        if (file_exists($logo_ipsp)) $this->Image($logo_ipsp, 10, 8, 30);
        if (file_exists($logo_uptm)) $this->Image($logo_uptm, 170, 8, 30);

        $this->SetFont('Arial', 'B', 11);
        $this->SetY(10);
        $this->MultiCell(0, 6, utf8_decode("Instituto de Previsión Social de los Profesores de la\nUniversidad Politécnica Territorial Kléber Ramirez del Estado Mérida"), 0, 'C');
        $this->SetFont('Arial', 'B', 13);
        $this->Cell(0, 8, utf8_decode($this->titulo), 0, 1, 'C');
        $this->Ln(3);
    }

    function Footer() {
        $this->SetY(-20);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 8, utf8_decode('Fecha de Emisión: ' . date('d-m-Y')), 0, 0, 'C');
        $this->Ln(5);
        $this->Cell(0, 8, 'Página ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Consulta
$sql = "SELECT e.nombre_especialidad, COUNT(c.id_especialidad) AS total_solicitudes
        FROM especialidades e
        JOIN citas c ON e.id_especialidad = c.id_especialidad
        WHERE c.fecha_cita >= DATE_SUB(CURDATE(), $interval)
        GROUP BY e.nombre_especialidad
        ORDER BY total_solicitudes DESC";
$resultado = $conn->query($sql);

if (ob_get_length()) ob_end_clean();

$pdf = new PDF_Especialidades($titulo);
$pdf->AliasNbPages();
$pdf->AddPage();

// Cabecera de tabla
$pdf->SetFillColor(6, 41, 116);
$pdf->SetTextColor(255);
$pdf->SetFont('Arial', 'B', 10);
$w = [130, 70];
$headers = ['Especialidad', 'Total Solicitudes'];
foreach ($headers as $i => $h)
    $pdf->Cell($w[$i], 8, utf8_decode($h), 1, 0, 'C', true);
$pdf->Ln();

$pdf->SetFillColor(224, 235, 255);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial', '', 10);
$fill = false;
if ($resultado) {
    while ($row = $resultado->fetch_assoc()) {
        $pdf->Cell($w[0], 7, utf8_decode($row['nombre_especialidad']), 'LR', 0, 'L', $fill);
        $pdf->Cell($w[1], 7, $row['total_solicitudes'], 'LR', 0, 'C', $fill);
        $pdf->Ln();
        $fill = !$fill;
    }
}
$pdf->Cell(array_sum($w), 0, '', 'T');

$pdf->Output('D', 'reporte_especialidades_' . $tipo_reporte . '_' . date('Ymd') . '.pdf');
?>