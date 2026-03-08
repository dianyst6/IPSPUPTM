<?php  
include 'C:/xampp/htdocs/IPSPUPTM/assets/fpdf.php';  
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';  

// Función para calcular el número de líneas que ocupara un MultiCell  
function NbLines($pdf, $w, $txt) {  
    $texto = str_replace("\r", '', $txt);  
    $nb = strlen($texto);  
    if ($nb > 0 and $texto[$nb-1] == "\n") {  
        $nb--;  
    }  
    $sep = -1;  
    $i = 0;  
    $j = 0;  
    $l = 0;  
    $nl = 1;  

    // En lugar de usar cMargin, calculamos el ancho máximo para texto directamente  
    $wmax = $w;  

    while ($i < $nb) {  
        $c = $texto[$i];  
        if ($c == "\n") {  
            $i++;  
            $sep = -1;  
            $j = $i;  
            $l = 0;  
            $nl++;  
            continue;  
        }  
        if ($c == ' ') {  
            $sep = $i;  
        }  
        $l += $pdf->GetStringWidth($c);  
        if ($l > $wmax) {  
            if ($sep == -1) {  
                if ($i == $j) {  
                    $i++;  
                }  
            } else {  
                $i = $sep + 1;  
            }  
            $sep = -1;  
            $j = $i;  
            $l = 0;  
            $nl++;  
        } else {  
            $i++;  
        }  
    }  
    return $nl;  
}  
// Crear instancia de FPDF  
$pdf = new FPDF();  
$pdf->AddPage();  
$pdf->SetFont('Arial', 'B', 12);  

// Título  
$pdf->Cell(0, 10, utf8_decode('Bitácora de actividades'), 0, 1, 'C');  
$pdf->Ln(10); // Salto de línea  

// Encabezados de la tabla  
$pdf->SetFont('Arial', 'B', 10);  
$pdf->Cell(50, 10, 'Usuario', 1, 0, 'C');  
$pdf->Cell(50, 10, utf8_decode('Acción'), 1, 0, 'C');  
$pdf->Cell(60, 10, utf8_decode('Descripción'), 1, 0, 'C');  
$pdf->Cell(30, 10, 'Fecha', 1, 1, 'C');  

// Obtener datos de la base de datos  
$sql = "SELECT usuario, accion, descripcion, fecha FROM bitacora ORDER BY fecha DESC";  
$result = $conn->query($sql);  

// Configurar fuente para datos  
$pdf->SetFont('Arial', '', 10);  

if ($result->num_rows > 0) {  
    while ($row = $result->fetch_assoc()) {  
        $usuario = utf8_decode($row['usuario']);  
        $accion = utf8_decode($row['accion']);  
        $descripcion = utf8_decode($row['descripcion']);  
        $fecha = utf8_decode(str_replace(' ', "\n", $row['fecha'])); // separar fecha y hora en líneas  

        // Calcular líneas necesarias por columna  
        $lineas_usuario = NbLines($pdf, 50, $usuario);  
        $lineas_accion = NbLines($pdf, 50, $accion);  
        $lineas_descripcion = NbLines($pdf, 60, $descripcion);  
        $lineas_fecha = NbLines($pdf, 30, $fecha);  

        $max_lineas = max($lineas_usuario, $lineas_accion, $lineas_descripcion, $lineas_fecha);  
        $altura_fila = $max_lineas * 6; // altura por línea (6 es un buen valor estimado)  

        // Guardar posición inicial  
        $x = $pdf->GetX();  
        $y = $pdf->GetY();  

        // Usuario  
        $pdf->Rect($x, $y, 50, $altura_fila);  
        $pdf->MultiCell(50, 6, $usuario, 0, 'C');  
        $pdf->SetXY($x + 50, $y);  

        // Acción  
        $pdf->Rect($x + 50, $y, 50, $altura_fila);  
        $pdf->MultiCell(50, 6, $accion, 0, 'C');  
        $pdf->SetXY($x + 100, $y);  

        // Descripción  
        $pdf->Rect($x + 100, $y, 60, $altura_fila);  
        $pdf->MultiCell(60, 6, $descripcion, 0, 'C');  
        $pdf->SetXY($x + 160, $y);  

        // Fecha  
        $pdf->Rect($x + 160, $y, 30, $altura_fila);  
        $pdf->MultiCell(30, 6, $fecha, 0, 'C');  

        // Mover a abajo para la siguiente fila  
        $pdf->SetXY($x, $y + $altura_fila);  
    }  
} else {  
    $pdf->Cell(0, 10, utf8_decode('No hay registros disponibles.'), 1, 1, 'C');  
}  

// Salida del archivo PDF  
$pdf->Output('D', 'bitacora.pdf');  
$conn->close();  
?>  
