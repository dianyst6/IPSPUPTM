<?php
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

if (isset($_POST['id_contrato'])) {
    $id = $_POST['id_contrato'];

    // 1. Obtener el monto total del contrato
    $sql_total = "SELECT monto_total FROM contrato_plan WHERE ID_contrato = '$id'";
    $res_total = mysqli_query($conn, $sql_total);
    $data_total = mysqli_fetch_assoc($res_total);
    $monto_total = $data_total['monto_total'] ?? 0;

    // 2. Calcular el 30% requerido como pago inicial
    $monto_inicial_requerido = $monto_total * 0.30;

    // 3. Sumar todos los pagos con tipo_pago = 'Pago Inicial'
    $sql_inicial = "SELECT COALESCE(SUM(monto_cuota), 0) as total_inicial 
                    FROM pagos_contrato 
                    WHERE ID_contrato = '$id' AND tipo_pago = 'Pago Inicial'";
    $res_inicial = mysqli_query($conn, $sql_inicial);
    $data_inicial = mysqli_fetch_assoc($res_inicial);
    $total_pagado_inicial = $data_inicial['total_inicial'];

    // 4. ¿Ya se completó el pago inicial?
    $pago_inicial_completo = ($total_pagado_inicial >= $monto_inicial_requerido);

    // 5. Saldo del pago inicial pendiente
    $saldo_inicial_pendiente = max(0, $monto_inicial_requerido - $total_pagado_inicial);

    // 6. Obtener la suma de cuotas normales y la última cuota registrada
    $sql_pagos = "SELECT COALESCE(SUM(monto_cuota), 0) as total_pagado, 
                         COALESCE(MAX(numero_cuota), 0) as ultima_cuota 
                  FROM pagos_contrato 
                  WHERE ID_contrato = '$id' AND tipo_pago = 'Cuota'";
    $res_pagos = mysqli_query($conn, $sql_pagos);
    $data_pagos = mysqli_fetch_assoc($res_pagos);
    
    $total_pagado_cuotas = $data_pagos['total_pagado'];
    $ultima_cuota = $data_pagos['ultima_cuota'];

    // 7. Saldo total pendiente (monto_total - todo lo pagado)
    $saldo_pendiente = $monto_total - $total_pagado_inicial - $total_pagado_cuotas;
    $siguiente_cuota = $ultima_cuota + 1;

    // 8. Devolvemos toda la info como JSON
    echo json_encode([
        'saldo'                   => number_format($saldo_pendiente, 2, '.', ''),
        'proxima_cuota'           => $siguiente_cuota,
        'pago_inicial_requerido'  => number_format($monto_inicial_requerido, 2, '.', ''),
        'pago_inicial_pagado'     => number_format($total_pagado_inicial, 2, '.', ''),
        'pago_inicial_pendiente'  => number_format($saldo_inicial_pendiente, 2, '.', ''),
        'pago_inicial_completo'   => $pago_inicial_completo
    ]);
}
?>