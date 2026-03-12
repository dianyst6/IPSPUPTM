<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once 'C:/xampp/htdocs/IPSPUPTM/assets/fpdf.php';
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

$titulo = "Reporte de Beneficiarios";

class PDF_Beneficiarios extends FPDF {
    protected $titulo;

    function __construct($titulo) {
        parent::__construct('L', 'mm', 'Letter'); // Landscape
        $this->titulo = $titulo;
    }

    function Header() {
        $logo_ipsp = 'C:/xampp/htdocs/IPSPUPTM/recursos/img/IPSPUPTMlogo.png';
        $logo_uptm = 'C:/xampp/htdocs/IPSPUPTM/recursos/img/UPTM_logo.png';
        if (file_exists($logo_ipsp))  $this->Image($logo_ipsp, 10, 6, 28);
        if (file_exists($logo_uptm))  $this->Image($logo_uptm, 245, 6, 28);

        $this->SetFont('Arial', 'B', 11);
        $this->SetY(8);
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
$sql = "SELECT p.cedula, p.nombre, p.apellido, p.fechanacimiento, p.genero,
               p.telefono, p.correo, p.ocupacion,
               CONCAT(pa.nombre, ' ', pa.apellido) AS afiliado_nombre_completo,
               b.created_at
        FROM beneficiarios b
        JOIN persona p ON b.cedula = p.cedula
        JOIN afiliados a ON b.cedula_afil = a.id
        JOIN persona pa ON a.cedula = pa.cedula
        ORDER BY b.created_at DESC";
$resultado = $conn->query($sql);

if (ob_get_length()) ob_end_clean();

$pdf = new PDF_Beneficiarios($titulo);
$pdf->AliasNbPages();
$pdf->AddPage();

// Cabecera de tabla
$pdf->SetFillColor(6, 41, 116);
$pdf->SetTextColor(255);
$pdf->SetFont('Arial', 'B', 7);
$w = [18, 26, 26, 22, 18, 22, 50, 26, 40, 30];
$headers = ['Cédula', 'Nombre', 'Apellido', 'F. Nacimiento', 'Género', 'Teléfono', 'Correo', 'Ocupación', 'Afiliado', 'Fecha Registro'];
foreach ($headers as $i => $h)
    $pdf->Cell($w[$i], 7, utf8_decode($h), 1, 0, 'C', true);
$pdf->Ln();

$pdf->SetFillColor(224, 235, 255);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial', '', 7);
$fill = false;
if ($resultado) {
    while ($row = $resultado->fetch_assoc()) {
        $pdf->Cell($w[0], 6, $row['cedula'], 'LR', 0, 'C', $fill);
        $pdf->Cell($w[1], 6, utf8_decode($row['nombre']), 'LR', 0, 'L', $fill);
        $pdf->Cell($w[2], 6, utf8_decode($row['apellido']), 'LR', 0, 'L', $fill);
        $pdf->Cell($w[3], 6, date('d-m-Y', strtotime($row['fechanacimiento'])), 'LR', 0, 'C', $fill);
        $pdf->Cell($w[4], 6, utf8_decode($row['genero']), 'LR', 0, 'C', $fill);
        $pdf->Cell($w[5], 6, $row['telefono'], 'LR', 0, 'C', $fill);
        $pdf->Cell($w[6], 6, $row['correo'], 'LR', 0, 'L', $fill);
        $pdf->Cell($w[7], 6, utf8_decode($row['ocupacion']), 'LR', 0, 'L', $fill);
        $pdf->Cell($w[8], 6, utf8_decode($row['afiliado_nombre_completo']), 'LR', 0, 'L', $fill);
        $pdf->Cell($w[9], 6, date('d-m-Y', strtotime($row['created_at'])), 'LR', 0, 'C', $fill);
        $pdf->Ln();
        $fill = !$fill;
    }
}
$pdf->Cell(array_sum($w), 0, '', 'T');

$pdf->Output('D', 'reporte_beneficiarios_' . date('Ymd') . '.pdf');
?>