<?php
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

if (isset($_POST['id_contrato'])) {
    $id = $_POST['id_contrato'];

    // 1. Obtener el monto total del contrato
    $sql_total = "SELECT monto_total FROM contrato_plan WHERE ID_contrato = '$id'";
    $res_total = mysqli_query($conn, $sql_total);
    $data_total = mysqli_fetch_assoc($res_total);
    $monto_total = $data_total['monto_total'] ?? 0;

    // 2. Obtener la suma pagada y la ÚLTIMA cuota registrada
    $sql_pagos = "SELECT SUM(monto_cuota) as total_pagado, MAX(numero_cuota) as ultima_cuota 
                  FROM pagos_contrato 
                  WHERE ID_contrato = '$id'";
    $res_pagos = mysqli_query($conn, $sql_pagos);
    $data_pagos = mysqli_fetch_assoc($res_pagos);
    
    $total_pagado = $data_pagos['total_pagado'] ?? 0;
    $ultima_cuota = $data_pagos['ultima_cuota'] ?? 0;

    // 3. Cálculos
    $saldo_pendiente = $monto_total - $total_pagado;
    $siguiente_cuota = $ultima_cuota + 1;

    // 4. Devolvemos ambos datos como un objeto JSON
    echo json_encode([
        'saldo' => number_format($saldo_pendiente, 2, '.', ''),
        'proxima_cuota' => $siguiente_cuota
    ]);
}
?>