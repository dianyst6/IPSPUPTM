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
            $this->Image($this->logo_ipspuptm, 10, 8, 33);
        }
        if (file_exists($this->logo_uptm)) {
            $this->Image($this->logo_uptm, 170, 8, 33);
        }
        
        // Arial bold 12
        $this->SetFont('Arial', 'B', 12);
        // Título
        $this->SetY(10);
        $this->MultiCell(0, 7, utf8_decode("Instituto de Previsión Social de los Profesores de la\nUniversidad Politécnica Territorial Kléber Ramirez del\nEstado Mérida"), 0, 'C');
        
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, utf8_decode($this->titulo), 0, 1, 'C');
        $this->Ln(5);
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
            $w = array(25, 60, 45, 20, 25, 20);
        } else {
            $w = array(25, 65, 55, 25, 25);
        }
        
        for($i=0;$i<count($header);$i++)
            $this->Cell($w[$i], 7, utf8_decode($header[$i]), 1, 0, 'C', true);
        $this->Ln();
        
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('Arial', '', 8);
        
        $fill = false;
        foreach($data as $row) {
            $this->Cell($w[0], 6, date('d-m-Y', strtotime($row['fecha'])), 'LR', 0, 'C', $fill);
            $this->Cell($w[1], 6, utf8_decode($row['paciente']), 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 6, utf8_decode($row['referencia']), 'LR', 0, 'L', $fill);
            if ($tipo_pago == 'contrato') {
                $this->Cell($w[3], 6, "Cuota #" . $row['cuota'], 'LR', 0, 'C', $fill);
                $this->Cell($w[4], 6, number_format($row['monto'], 2) . " Bs", 'LR', 0, 'R', $fill);
                $this->Cell($w[5], 6, utf8_decode($row['metodo']), 'LR', 0, 'C', $fill);
            } else {
                $this->Cell($w[3], 6, number_format($row['monto'], 2) . " Bs", 'LR', 0, 'R', $fill);
                $this->Cell($w[4], 6, utf8_decode($row['metodo']), 'LR', 0, 'C', $fill);
            }
            $this->Ln();
            $fill = !$fill;
        }
        $this->Cell(array_sum($w), 0, '', 'T');
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
