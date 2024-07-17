<?php
include 'config.php';

$success = false; // Variable para verificar si el formulario fue enviado con éxito

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];

    $sql = "INSERT INTO usuarios (nombre, email, password, rol) VALUES ('$nombre', '$email', '$password', '$rol')";

    if ($conn->query($sql) === TRUE) {
        $success = true; // Marcar que el formulario fue enviado con éxito
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro</title>
  <link href="https://unpkg.com/tailwindcss@^2.0/dist/tailwind.min.css" rel="stylesheet">
  <script>
    function clearForm() {
      document.getElementById('registrationForm').reset();
    }

    <?php if ($success): ?>
      window.onload = function() {
        clearForm();
        alert('Registro exitoso. Ahora puede iniciar sesión.');
        window.location.href = 'login.php';
      }
    <?php endif; ?>
  </script>
</head>
<body>
  <div class="container mx-auto px-4 py-8 flex justify-center items-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 bg-white rounded shadow-md">
      <h2 class="text-2xl font-bold text-center mb-4">Registro</h2>
      <form method="POST" action="" id="registrationForm" class="space-y-4">
        <div class="flex flex-col">
          <label for="nombre" class="text-sm font-medium mb-2">Nombre:</label>
          <input type="text" name="nombre" id="nombre" placeholder="Ingrese su nombre completo" required class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:ring-1">
        </div>
        <div class="flex flex-col">
          <label for="email" class="text-sm font-medium mb-2">Email:</label>
          <input type="email" name="email" id="email" placeholder="Ingrese su email" required class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:ring-1">
        </div>
        <div class="flex flex-col">
          <label for="password" class="text-sm font-medium mb-2">Contraseña:</label>
          <input type="password" name="password" id="password" placeholder="Ingrese su contraseña" required class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:ring-1">
        </div>
        <div class="flex flex-col">
          <label for="rol" class="text-sm font-medium mb-2">Rol:</label>
          <select name="rol" id="rol" required class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:ring-1">
            <option value="asistente">Asistente</option>
            <option value="organizador">Organizador</option>
          </select>
        </div>
        <button type="submit" class="w-full py-2 px-4 bg-yellow-600 text-white font-bold rounded hover:bg-yellow-700">Registrarse</button>
      </form>
    </div>
  </div>
</body>
</html>
