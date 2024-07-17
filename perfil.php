<?php
session_start();
include 'config.php';
include 'templates/header.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $nombre = $_POST['nombre'];
  $foto_perfil = $_POST['foto_perfil'];

  $sql = "UPDATE usuarios SET nombre='$nombre', foto_perfil='$foto_perfil' WHERE id='$user_id'";

  if ($conn->query($sql) === TRUE) {
    echo "Perfil actualizado exitosamente";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

$sql = "SELECT * FROM usuarios WHERE id='$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Perfil</title>
  <link href="https://unpkg.com/tailwindcss@^2.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100"> <div class="container mx-auto px-4 py-8">
    <div class="flex flex-col justify-center items-center">
      <h2 class="text-2xl font-bold mb-4 text-center">Editar Perfil</h2> <?php if (!empty($user['foto_perfil'])): ?>
        <img src="<?php echo $user['foto_perfil']; ?>" alt="Foto de perfil" class="rounded-full w-32 h-32 object-cover mx-auto mb-4">
      <?php endif; ?>
      <form method="POST" action="" class="w-full max-w-md bg-white shadow rounded-lg px-8 py-5 mb-4"> <div class="mb-4">
          <label for="nombre" class="block text-gray-700 text-sm font-bold mb-2">Nombre</label>
          <input type="text" id="nombre" name="nombre" value="<?php echo $user['nombre']; ?>" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-blue-500 focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="mb-4">
          <label for="foto_perfil" class="block text-gray-700 text-sm font-bold mb-2">URL de la foto de perfil</label>
          <input type="text" id="foto_perfil" name="foto_perfil" value="<?php echo $user['foto_perfil']; ?>" placeholder="URL de la foto de perfil" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-blue-500 focus:ring-2 focus:ring-blue-500">
        </div>
        <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-yellow-500">Actualizar</button>
      </form>
    </div>
  </div>
</body>
</html>



