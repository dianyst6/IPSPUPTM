<?php  
// get_usuario.php  
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';  

header('Content-Type: application/json');  

if (!isset($_GET['id'])) {  
    echo json_encode(['error' => 'ID no especificado']);  
    exit;  
}  

$id = intval($_GET['id']);  

// Consulta con join para traer nombre rol, ajusta según tu BD  
$sql = "SELECT u.Nombre_completo, u.username, r.nombre as role_name  
        FROM usuarios u  
        LEFT JOIN roles r ON u.role_id = r.id  
        WHERE u.id = ?";  

$stmt = $conn->prepare($sql);  
if (!$stmt) {  
    echo json_encode(['error' => 'Error en la preparación de la consulta']);  
    exit;  
}  

$stmt->bind_param('i', $id);  
$stmt->execute();  
$result = $stmt->get_result();  

if ($result->num_rows === 0) {  
    echo json_encode(['error' => 'Usuario no encontrado']);  
    exit;  
}  

$usuario = $result->fetch_assoc();  
echo json_encode($usuario);  
?>  