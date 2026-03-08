<?php

// Configuración de la conexión a la base de datos
$host = "localhost";
$db_name = "ipsp";
$username = "root";
$password = "";

// Inicializar conexión con mysqli
$conn = new mysqli($host, $username, $password, $db_name);

// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

// Configurar la conexión para que use UTF-8
$conn->set_charset("utf8");
?>
