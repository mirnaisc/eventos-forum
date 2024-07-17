<?php
session_start();
include 'config.php'; // Archivo con la configuración de la base de datos
include 'templates/header.php';

// Redirigir al usuario si no ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['rol']; // Asumiendo que el rol del usuario está almacenado en la sesión

// Consulta para obtener los eventos en los que el usuario está registrado (como asistente)
$sqlAsistente = "SELECT eventos.titulo, eventos.descripcion, eventos.fecha, eventos.hora, eventos.lugar 
                 FROM eventos 
                 INNER JOIN registros ON eventos.id = registros.evento_id 
                 WHERE registros.usuario_id = ?";
$stmtAsistente = $conn->prepare($sqlAsistente);
$stmtAsistente->bind_param("i", $user_id);
$stmtAsistente->execute();
$resultAsistente = $stmtAsistente->get_result();

// Consulta para obtener los eventos creados por el organizador (si el usuario es organizador)
$sqlOrganizador = "SELECT * FROM eventos WHERE organizador_id = ?";
$stmtOrganizador = $conn->prepare($sqlOrganizador);
$stmtOrganizador->bind_param("i", $user_id);
$stmtOrganizador->execute();
$resultOrganizador = $stmtOrganizador->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link href="https://unpkg.com/tailwindcss@^2.0/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <style>
    /* Estilo del cuerpo con color de fondo */
    body {
      background-color: #f0f4f8; /* Color de fondo agradable a la vista */
    }
    /* Estilo del encabezado */
    header {
      background-color: rgba(255, 255, 255, 0.8); /* Fondo semi-transparente para el encabezado */
      padding: 1rem; /* Espaciado dentro del encabezado */
    }
  </style>
</head>
<body>
  <header class="px-4 py-2 mb-4">
    <div class="container mx-auto flex justify-between items-center">
      <h2 class="text-2xl font-bold text-gray-800"><i class="fas fa-calendar-alt"></i> Bienvenido</h2>
    </div> 
  </header>

  <div class="container mx-auto px-4 py-8">
    <?php if ($user_role == 'asistente' || $user_role == 'ambos'): ?>
    <div class="mt-6">
      <h3 class="text-xl font-semibold text-gray-800 mb-4"><i class="fas fa-calendar-check"></i> Eventos Registrados</h3>
      <?php if ($resultAsistente->num_rows > 0): ?>
        <ul class="space-y-4">
          <?php while ($row = $resultAsistente->fetch_assoc()): ?>
            <li class="border border-gray-300 rounded-lg p-4 bg-white shadow flex items-center">
              <div class="mr-4">
                <i class="fas fa-calendar-day text-4xl text-gray-800"></i>
              </div>
              <div>
                <h4 class="text-lg font-bold text-gray-900"><?php echo htmlspecialchars($row['titulo']); ?></h4>
                <p class="text-gray-700"><?php echo htmlspecialchars($row['descripcion']); ?></p>
                <p class="text-gray-600">Fecha: <?php echo htmlspecialchars($row['fecha']); ?> Hora: <?php echo htmlspecialchars($row['hora']); ?></p>
                <p class="text-gray-600">Lugar: <?php echo htmlspecialchars($row['lugar']); ?></p>
              </div>
            </li>
          <?php endwhile; ?>
        </ul>
      <?php else: ?>
        <p class="text-gray-600">No estás registrado en ningún evento.</p>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if ($user_role == 'organizador' || $user_role == 'ambos'): ?>
    <div class="mt-6">
      <h3 class="text-xl font-semibold text-gray-800 mb-4"><i class="fas fa-calendar-plus"></i> Tus Eventos como Organizador</h3>
      <?php if ($resultOrganizador->num_rows > 0): ?>
        <?php while ($evento = $resultOrganizador->fetch_assoc()): ?>
          <div class="border border-yellow-300 rounded-lg p-4 bg-yellow-100 shadow mb-6">
            <h4 class="text-lg font-bold text-gray-900"><i class="fas fa-calendar-alt"></i> <?php echo htmlspecialchars($evento['titulo']); ?></h4>
            <p class="text-gray-700"><?php echo htmlspecialchars($evento['descripcion']); ?></p>
            <p class="text-gray-600">Fecha: <?php echo htmlspecialchars($evento['fecha']); ?> Hora: <?php echo htmlspecialchars($evento['hora']); ?></p>
            <p class="text-gray-600">Lugar: <?php echo htmlspecialchars($evento['lugar']); ?></p>
            
            <h5 class="text-md font-semibold text-gray-800 mt-4"><i class="fas fa-users"></i> Usuarios Registrados</h5>
            <?php
            // Consulta para obtener los usuarios registrados en el evento actual
            $sqlUsuarios = "SELECT usuarios.nombre, usuarios.email 
                            FROM registros 
                            INNER JOIN usuarios ON registros.usuario_id = usuarios.id 
                            WHERE registros.evento_id = ?";
            $stmtUsuarios = $conn->prepare($sqlUsuarios);
            $stmtUsuarios->bind_param("i", $evento['id']);
            $stmtUsuarios->execute();
            $resultUsuarios = $stmtUsuarios->get_result();
            ?>
            <?php if ($resultUsuarios->num_rows > 0): ?>
              <ul class="list-disc list-inside mt-2">
                <?php while ($usuario = $resultUsuarios->fetch_assoc()): ?>
                  <li class="text-gray-700"><?php echo htmlspecialchars($usuario['nombre']); ?> (<?php echo htmlspecialchars($usuario['email']); ?>)</li>
                <?php endwhile; ?>
              </ul>
            <?php else: ?>
              <p class="text-gray-600">No hay usuarios registrados en este evento.</p>
            <?php endif; ?>
            <?php $stmtUsuarios->close(); ?>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-gray-600">No has creado ningún evento.</p>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php 
    $stmtAsistente->close();
    $stmtOrganizador->close();
    $conn->close(); 
    ?>
  </div>
</body>
</html>
