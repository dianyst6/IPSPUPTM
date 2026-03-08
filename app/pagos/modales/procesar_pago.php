<?php
// 1. Incluimos la conexión (Ajusta la ruta si es necesario)
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';

// Verificar que los datos vengan por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 2. Recibimos los datos de los formularios mediante sus 'name'
    $id_contrato  = $_POST['id_contrato'];
    $monto_cuota  = $_POST['monto_cuota'];
    $fecha_pago   = $_POST['fecha_pago'];
    $numero_cuota = $_POST['numero_cuota'];
    $metodo_pago  = $_POST['metodo_pago'];

    // 3. Validamos que los campos esenciales no estén vacíos
    if (!empty($id_contrato) && !empty($monto_cuota) && !empty($numero_cuota)) {
        
        // 4. Preparamos la consulta SQL
        // Según tu base de datos: ID_contrato, monto_cuota, fecha_pago, numero_cuota, metodo_pago
        $sql = "INSERT INTO pagos_contrato (ID_contrato, monto_cuota, fecha_pago, numero_cuota, metodo_pago) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $sql);
        
        if ($stmt) {
            /* Tipos de datos para bind_param:
               i = entero (ID_contrato)
               d = doble/decimal (monto_cuota)
               s = string (fecha_pago)
               i = entero (numero_cuota)
               s = string (metodo_pago)
            */
            mysqli_stmt_bind_param($stmt, "idsis", $id_contrato, $monto_cuota, $fecha_pago, $numero_cuota, $metodo_pago);

            // 5. Ejecutamos la consulta
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['flash_msg'] = "Pago registrado con éxito";
                $_SESSION['flash_type'] = "success";
                header("Location: /IPSPUPTM/home.php?vista=gestionpagoscontrato");
                exit();
            } else {
                echo "Error al ejecutar la consulta: " . mysqli_error($conn);
            }

            mysqli_stmt_close($stmt);
        } else {
            echo "Error al preparar la consulta: " . mysqli_error($conn);
        }

    } else {
        echo "<script>
                alert('Error: Todos los campos son obligatorios.');
                window.history.back();
              </script>";
    }
}

// Cerramos la conexión
mysqli_close($conn);
?>