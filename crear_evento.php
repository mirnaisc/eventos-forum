<?php
session_start();
include 'config.php';
include 'templates/header.php';

// Asegúrate de que la ruta sea correcta
require 'Composer/index.php';

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 'organizador') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $lugar = $_POST['lugar'];
    $capacidad = $_POST['capacidad'];
    $organizador_id = $_SESSION['user_id'];

    $asuntoEmail = 'El evento ' . $titulo . ' ha sido creado exitosamente';
    $bodyEmail = 'Estimado organizador, el evento ' . $titulo . ' ha sido creado exitosamente, para más información ingrese a la plataforma.';

    $sql = "INSERT INTO eventos (titulo, descripcion, fecha, hora, lugar, capacidad, organizador_id) 
            VALUES ('$titulo', '$descripcion', '$fecha', '$hora', '$lugar', '$capacidad', '$organizador_id')";

    $result = $conn->query("SELECT email FROM usuarios WHERE id = '$organizador_id'");
    $row = $result->fetch_assoc();
    $correoOrganizador = $row['email'];

    if ($conn->query($sql) === TRUE) {
        // Enviar correo
        $resultadoCorreo = enviarCorreo($asuntoEmail, $bodyEmail, $correoOrganizador);

        // Mostrar resultado del correo a través de un alert
        echo "<script>alert('$resultadoCorreo');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Evento</title>
    <link href="https://unpkg.com/tailwindcss@^2.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-6">Crear Evento</h2>
        <form method="POST" action="" class="space-y-6">
            <div class="flex flex-col">
                <label for="titulo" class="text-sm font-medium mb-1">Nombre del Evento:</label>
                <input type="text" name="titulo" id="titulo" placeholder="Ingrese el título del evento" required class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:ring-1">
            </div>
            <div class="flex flex-col">
                <label for="descripcion" class="text-sm font-medium mb-1">Descripción:</label>
                <textarea name="descripcion" id="descripcion" placeholder="Ingrese una descripción detallada del evento" required class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:ring-1 h-24"></textarea>
            </div>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="flex flex-col">
                    <label for="fecha" class="text-sm font-medium mb-1">Fecha:</label>
                    <input type="date" name="fecha" id="fecha" required class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:ring-1">
                </div>
                <div class="flex flex-col">
                    <label for="hora" class="text-sm font-medium mb-1">Hora:</label>
                    <input type="time" name="hora" id="hora" required class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:ring-1">
                </div>
            </div>
            <div class="flex flex-col">
                <label for="lugar" class="text-sm font-medium mb-1">Lugar:</label>
                <input type="text" name="lugar" id="lugar" placeholder="Ingrese el lugar del evento" required class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:ring-1">
            </div>
            <div class="flex flex-col">
                <label for="capacidad" class="text-sm font-medium mb-1">Capacidad Máxima:</label>
                <input type="number" name="capacidad" id="capacidad" placeholder="Ingrese la capacidad máxima de asistentes" required class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:ring-1">
            </div>
            <button type="submit" class="w-full py-2 px-4 bg-yellow-600 text-white font-bold rounded hover:bg-yellow-700">Crear Evento</button>
        </form>
    </div>
</body>
</html>
