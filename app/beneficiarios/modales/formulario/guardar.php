<?php  
require 'C:/xampp/htdocs/IPSPUPTM/config/database.php';  
include 'C:/xampp/htdocs/IPSPUPTM/app/configuracion/bitacora/bitacora.php';  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {  
    if (isset($_POST['cedula_afil'], $_POST['cedula'], $_POST['nombre'], $_POST['apellido'], $_POST['fechanacimiento'], $_POST['parentesco'], $_POST['genero'], $_POST['telefono'], $_POST['correo'], $_POST['ocupacion'])) {  
        // Limpiar datos
        $cedula_afil = htmlspecialchars($_POST['cedula_afil']);  
        $cedula = htmlspecialchars($_POST['cedula']);  
        $nombre = htmlspecialchars($_POST['nombre']);  
        $apellido = htmlspecialchars($_POST['apellido']);  
        $fechanacimiento = htmlspecialchars($_POST['fechanacimiento']);  
        $parentesco = htmlspecialchars($_POST['parentesco']);  
        $genero = htmlspecialchars($_POST['genero']);  
        $telefono = htmlspecialchars($_POST['telefono']);  
        $correo = htmlspecialchars($_POST['correo']);  
        $ocupacion = htmlspecialchars($_POST['ocupacion']);  

        // Verificar si la persona ya existe
        $sql_check_persona = "SELECT cedula FROM persona WHERE cedula = ?";  
        $stmt_check = $conn->prepare($sql_check_persona);  
        $stmt_check->bind_param("s", $cedula);  
        $stmt_check->execute();  
        $result_check = $stmt_check->get_result();  

        if ($result_check->num_rows === 0) {  
            // Insertar nueva persona
            $sql_persona = "INSERT INTO persona (cedula, nombre, apellido, fechanacimiento, genero, telefono, correo, ocupacion) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";  
            $stmt_persona = $conn->prepare($sql_persona);  
            $stmt_persona->bind_param("ssssssss", $cedula, $nombre, $apellido, $fechanacimiento, $genero, $telefono, $correo, $ocupacion);  
            
            if (!$stmt_persona->execute()) {  
                die("Error en persona: " . $stmt_persona->error);  
            }  
            $stmt_persona->close();  
        }  

        // Insertar beneficiario
        $sql_beneficiarios = "INSERT INTO beneficiarios (cedula_afil, parentesco, cedula, created_at, updated_at) 
                            VALUES (?, ?, ?, NOW(), NOW())";  
        $stmt_beneficiarios = $conn->prepare($sql_beneficiarios);  
        $stmt_beneficiarios->bind_param("iss", $cedula_afil, $parentesco, $cedula);  

        if ($stmt_beneficiarios->execute()) {  
            // Registrar en bitácora
            $usuario = $_SESSION['username']; 
            $accion = "Registro de Beneficiario";
            $descripcion = "Cédula: $cedula, Nombre: $nombre, Apellido: $apellido";
            registrarenBitacora($conn, $usuario, $accion, $descripcion);
            
            header('Location: /IPSPUPTM/home.php?vista=beneficiarios');  
            exit();  
        } else {  
            die("Error en beneficiarios: " . $stmt_beneficiarios->error);  
        }  

        $stmt_check->close();  
        $stmt_beneficiarios->close();  
    } else {  
        die("Faltan datos requeridos");  
    }  
} else {  
    die("Método no permitido");  
}  

$conn->close();  
?>