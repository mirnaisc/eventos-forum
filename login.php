<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuarios WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['rol'] = $user['rol'];
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Contraseña incorrecta";
        }
    } else {
        echo "No se encontró el usuario";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar Sesión</title>
  <link href="https://unpkg.com/tailwindcss@^2.0/dist/tailwind.min.css" rel="stylesheet">
  <style>
    /* Establece la imagen de fondo y ajusta el estilo del cuerpo */
    body {
      background-image: url('forum.jpg'); /* Ruta a tu imagen de fondo */
      background-size: cover; /* Ajusta la imagen para cubrir todo el fondo */
      background-position: center; /* Centra la imagen en el fondo */
      background-repeat: no-repeat; /* Evita repetir la imagen */
      height: 100vh; /* Ajusta la altura al 100% de la ventana */
      width: 100vw; /* Ajusta el ancho al 100% de la ventana */
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0; /* Elimina el margen predeterminado del cuerpo */
      padding: 0; /* Elimina el relleno predeterminado del cuerpo */
    }

    .transparent-container {
      background-color: rgba(255, 255, 255, 0.8); /* Fondo semi-transparente para el contenedor */
      padding: 2rem; /* Espaciado dentro del contenedor */
      border-radius: 8px; /* Borde redondeado */
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Sombra para dar un efecto de profundidad */
    }
  </style>
</head>
<body>
  <div class="transparent-container mx-auto max-w-md">
    <h2 class="text-2xl font-bold text-center mb-4">Iniciar Sesión</h2>
    <form method="POST" action="" class="space-y-4">
      <div class="flex flex-col">
        <label for="email" class="text-sm font-medium mb-2">Email:</label>
        <input type="email" name="email" id="email" placeholder="Ingrese su email" required class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-yellow-500 focus:ring-1">
      </div>
      <div class="flex flex-col">
        <label for="password" class="text-sm font-medium mb-2">Contraseña:</label>
        <input type="password" name="password" id="password" placeholder="Ingrese su contraseña" required class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-yellow-500 focus:ring-1">
      </div>
      <button type="submit" class="w-full py-2 px-4 bg-yellow-600 text-white font-bold rounded hover:bg-yellow-700">Iniciar Sesión</button>
    </form>
  </div>
</body>
</html>
