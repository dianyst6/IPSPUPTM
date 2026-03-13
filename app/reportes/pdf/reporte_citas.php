<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once 'C:/xampp/htdocs/IPSPUPTM/assets/fpdf.php';
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

$tipo_reporte = isset($_GET['tipo_reporte']) && in_array($_GET['tipo_reporte'], ['semanal','quincenal','mensual'])
                ? $_GET['tipo_reporte'] : 'semanal';

$titulo_base = "Reporte de Citas";
switch ($tipo_reporte) {
    case 'semanal':   $interval = 'INTERVAL 1 WEEK';  $titulo = "$titulo_base - Semanal";   break;
    case 'quincenal': $interval = 'INTERVAL 2 WEEK';  $titulo = "$titulo_base - Quincenal"; break;
    case 'mensual':   $interval = 'INTERVAL 1 MONTH'; $titulo = "$titulo_base - Mensual";   break;
}

class PDF_Citas extends FPDF {
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
$sql = "SELECT c.fecha_cita, c.descripcion, e.nombre_especialidad,
               CASE WHEN ca.idcita IS NOT NULL THEN 'Afiliado' WHEN cb.idcita IS NOT NULL THEN 'Beneficiario' END AS tipo_paciente,
               CASE WHEN ca.idcita IS NOT NULL THEN CONCAT(p_a.nombre,' ',p_a.apellido) WHEN cb.idcita IS NOT NULL THEN CONCAT(p_b.nombre,' ',p_b.apellido) END AS nombre_paciente,
               CASE WHEN ca.idcita IS NOT NULL THEN p_a.cedula WHEN cb.idcita IS NOT NULL THEN p_b.cedula END AS cedula_paciente
        FROM citas c
        LEFT JOIN citas_afil ca ON c.id_cita = ca.idcita
        LEFT JOIN afiliados a ON ca.id_afiliado = a.id
        LEFT JOIN persona p_a ON a.cedula = p_a.cedula
        LEFT JOIN citas_benef cb ON c.id_cita = cb.idcita
        LEFT JOIN beneficiarios b ON cb.id_beneficiario = b.id
        LEFT JOIN persona p_b ON b.cedula = p_b.cedula
        LEFT JOIN especialidades e ON c.id_especialidad = e.id_especialidad
        WHERE c.fecha_cita >= DATE_SUB(CURDATE(), $interval)
        ORDER BY c.fecha_cita DESC";
$resultado = $conn->query($sql);

if (ob_get_length()) ob_end_clean();

$pdf = new PDF_Citas($titulo);
$pdf->AliasNbPages();
$pdf->AddPage();

// Cabecera de tabla
$pdf->SetFillColor(6, 41, 116);
$pdf->SetTextColor(255);
$pdf->SetFont('Arial', 'B', 8);
$w = [25, 45, 20, 20, 35, 55];
$headers = ['Fecha', 'Paciente', 'Cédula', 'Tipo', 'Especialidad', 'Descripción'];
foreach ($headers as $i => $h)
    $pdf->Cell($w[$i], 7, utf8_decode($h), 1, 0, 'C', true);
$pdf->Ln();

$pdf->SetFillColor(224, 235, 255);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial', '', 8);
$fill = false;
if ($resultado) {
    while ($row = $resultado->fetch_assoc()) {
        $pdf->Cell($w[0], 6, date('d-m-Y', strtotime($row['fecha_cita'])), 'LR', 0, 'C', $fill);
        $pdf->Cell($w[1], 6, utf8_decode($row['nombre_paciente']), 'LR', 0, 'L', $fill);
        $pdf->Cell($w[2], 6, $row['cedula_paciente'], 'LR', 0, 'C', $fill);
        $pdf->Cell($w[3], 6, utf8_decode($row['tipo_paciente']), 'LR', 0, 'C', $fill);
        $pdf->Cell($w[4], 6, utf8_decode($row['nombre_especialidad']), 'LR', 0, 'L', $fill);
        $pdf->Cell($w[5], 6, utf8_decode($row['descripcion']), 'LR', 0, 'L', $fill);
        $pdf->Ln();
        $fill = !$fill;
    }
}
$pdf->Cell(array_sum($w), 0, '', 'T');

$pdf->Output('D', 'reporte_citas_' . $tipo_reporte . '_' . date('Ymd') . '.pdf');
?>