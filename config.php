<?php
$servername = "localhost";  // El servidor donde está alojada tu base de datos
$username = "root";         // El nombre de usuario de tu base de datos
$password = "";             // La contraseña de tu base de datos
$dbname = "plataforma";     // El nombre de tu base de datos

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
