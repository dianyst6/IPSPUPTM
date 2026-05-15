<?php
ob_start(); // Prevenir cualquier salida accidental que corrompa el PDF
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once 'C:/xampp/htdocs/IPSPUPTM/assets/fpdf.php';
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

// Obtener el tipo de pago (contrato o externo)
$tipo_pago = $_GET['tipo_pago'] ?? 'contrato';

$titulo_base = "Reporte de Pagos";
$titulo = ($tipo_pago == 'contrato') ? "$titulo_base de Contratos" : "$titulo_base Externos";

$condicion_fecha_contrato = "";
$condicion_fecha_externo = "";

if (isset($_GET['fecha_inicio']) && isset($_GET['fecha_fin'])) {
    $fecha_inicio = $conn->real_escape_string($_GET['fecha_inicio']);
    $fecha_fin = $conn->real_escape_string($_GET['fecha_fin']);
    $condicion_fecha_contrato = "WHERE pc.fecha_pago BETWEEN '$fecha_inicio' AND '$fecha_fin'";
    $condicion_fecha_externo = "WHERE p.fecha_pago BETWEEN '$fecha_inicio' AND '$fecha_fin'";
    $titulo .= " - Del " . date('d/m/Y', strtotime($fecha_inicio)) . " al " . date('d/m/Y', strtotime($fecha_fin));
}

class PDF extends FPDF {
    protected $titulo;
    protected $logo_ipspuptm;
    protected $logo_uptm;

    function __construct($titulo, $orientation='P', $unit='mm', $size='Letter') {
        parent::__construct($orientation, $unit, $size);
        $this->titulo = $titulo;
        $this->logo_ipspuptm = 'C:/xampp/htdocs/IPSPUPTM/recursos/img/IPSPUPTMlogo.png';
        $this->logo_uptm = 'C:/xampp/htdocs/IPSPUPTM/recursos/img/UPTM_logo.png';
    }

    // Cabecera de página
    function Header() {
        // Logos
        if (file_exists($this->logo_ipspuptm)) {
            $this->Image($this->logo_ipspuptm, 10, 4, 25);
        }
        if (file_exists($this->logo_uptm)) {
            $this->Image($this->logo_uptm, 175, 4, 25);
        }
        
        // Arial bold 12
        $this->SetFont('Arial', 'B', 12);
        // Título
        $this->SetY(10);
        $this->SetTextColor(51, 51, 51);
        $this->SetX(40);
        $this->MultiCell(135, 5, utf8_decode("Instituto de Previsión Social de los Profesores de la\nUniversidad Politécnica Territorial Kléber Ramirez del\nEstado Mérida"), 0, 'C');
        
        $this->Ln(3);
        $this->SetFont('Arial', 'B', 14);
        $this->SetX(40);
        $this->Cell(135, 8, utf8_decode($this->titulo), 0, 1, 'C');
        
        $this->SetY(33);
        $this->SetDrawColor(6, 41, 116);
        $this->SetLineWidth(1.5);
        $this->Line(10, $this->GetY(), $this->GetPageWidth() - 10, $this->GetY());
        $this->Ln(6);
    }

    // Pie de página
    function Footer() {
        $this->SetY(-25);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Fecha de Emisión: ' . date('d-m-Y')), 0, 0, 'C');
        $this->Ln(5);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function FancyTable($header, $data, $tipo_pago) {
        $this->SetFillColor(6, 41, 116);
        $this->SetTextColor(255);
        $this->SetDrawColor(0);
        $this->SetLineWidth(.3);
        $this->SetFont('', 'B', 9);
        
        if ($tipo_pago == 'contrato') {
            $w = array(20, 55, 45, 20, 25, 25); // Sum: 190
        } else {
            $w = array(20, 65, 55, 25, 25); // Sum: 190
        }
        
        $pageWidth = $this->GetPageWidth();
        $tableWidth = array_sum($w);
        $marginLeft = ($pageWidth - $tableWidth) / 2;

        $this->SetX($marginLeft);
        for($i=0;$i<count($header);$i++)
            $this->Cell($w[$i], 9, utf8_decode($header[$i]), 0, 0, 'C', true);
        $this->Ln();
        
        $this->SetFillColor(235, 242, 250);
        $this->SetTextColor(30, 30, 30);
        $this->SetDrawColor(150, 150, 150);
        $this->SetLineWidth(0.3);
        $this->SetFont('Arial', '', 8);
        
        $fill = false;
        foreach($data as $row) {
            $this->SetX($marginLeft);
            $this->Cell($w[0], 8, date('d-m-Y', strtotime($row['fecha'])), 'B', 0, 'C', $fill);
            $paciente = utf8_decode(mb_strimwidth($row['paciente'], 0, 30, "...", 'UTF-8'));
            $this->Cell($w[1], 8, $paciente, 'B', 0, 'L', $fill);
            $referencia = utf8_decode(mb_strimwidth($row['referencia'], 0, 25, "...", 'UTF-8'));
            $this->Cell($w[2], 8, $referencia, 'B', 0, 'L', $fill);
            if ($tipo_pago == 'contrato') {
                $this->Cell($w[3], 8, "Cuota #" . $row['cuota'], 'B', 0, 'C', $fill);
                $this->Cell($w[4], 8, number_format($row['monto'], 2) . " Bs", 'B', 0, 'R', $fill);
                $this->Cell($w[5], 8, utf8_decode($row['metodo']), 'B', 0, 'C', $fill);
            } else {
                $this->Cell($w[3], 8, number_format($row['monto'], 2) . " Bs", 'B', 0, 'R', $fill);
                $this->Cell($w[4], 8, utf8_decode($row['metodo']), 'B', 0, 'C', $fill);
            }
            $this->Ln();
            $fill = !$fill;
        }
    }
}

// Consulta según el tipo de pago
$data = array();
if ($tipo_pago == 'contrato') {
    $sql = "SELECT 
                pc.fecha_pago,
                pc.monto_cuota AS monto,
                pc.numero_cuota,
                pc.metodo_pago,
                CONCAT(p.nombre, ' ', p.apellido) AS nombre_paciente,
                pl.nombre_plan AS referencia
            FROM pagos_contrato pc
            INNER JOIN contrato_plan cp ON pc.ID_contrato = cp.ID_contrato
            INNER JOIN afiliados af ON cp.ID_afiliado_contrato = af.cedula
            INNER JOIN persona p ON af.cedula = p.cedula
            INNER JOIN planes pl ON cp.ID_planes_contrato = pl.ID_planes
            $condicion_fecha_contrato
            ORDER BY pc.fecha_pago DESC";
    $header = array('Fecha', 'Paciente', 'Plan', 'Cuota', 'Monto', 'Método');
} else {
    $sql = "SELECT 
                p.fecha_pago,
                p.monto_final AS monto,
                e.nombre_especialidad AS referencia,
                p.metodo_pago,
                CONCAT(u.nombre, ' ', u.apellido) AS nombre_paciente
            FROM pagos_externos p
            INNER JOIN citas c ON p.id_cita = c.id_cita
            INNER JOIN citas_uptm h ON c.id_cita = h.idcita
            INNER JOIN comunidad_uptm u ON h.id_externo = u.id
            INNER JOIN especialidades e ON c.id_especialidad = e.id_especialidad
            $condicion_fecha_externo
            ORDER BY p.fecha_pago DESC";
    $header = array('Fecha', 'Paciente', 'Especialidad', 'Monto', 'Método');
}

$resultado = $conn->query($sql);
if ($resultado) {
    while ($row = $resultado->fetch_assoc()) {
        $item = array(
            'fecha' => $row['fecha_pago'],
            'paciente' => $row['nombre_paciente'],
            'referencia' => $row['referencia'],
            'monto' => $row['monto'],
            'metodo' => $row['metodo_pago']
        );
        if ($tipo_pago == 'contrato') {
            $item['cuota'] = $row['numero_cuota'];
        }
        $data[] = $item;
    }
}

// Limpiar el buffer para asegurar que no haya salida previa
if (ob_get_length()) ob_end_clean();

// Creación del objeto de la clase heredada
$pdf = new PDF($titulo);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);
if (empty($data)) {
    $pdf->Cell(0, 10, utf8_decode('No hay registros de pagos para mostrar en el periodo seleccionado.'), 0, 1, 'C');
} else {
    $pdf->FancyTable($header, $data, $tipo_pago);
}

$filename = 'reporte_pagos_' . $tipo_pago . '_' . date('Ymd') . '.pdf';
$pdf->Output('D', $filename);
?>
