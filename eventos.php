<?php
session_start();
include 'config.php';
include 'templates/header.php';

// Redirigir al usuario si no ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Obtener los eventos en los que el usuario ya está registrado
$sql = "SELECT evento_id FROM registros WHERE usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$registered_event_ids = [];
while ($row = $result->fetch_assoc()) {
    $registered_event_ids[] = $row['evento_id'];
}
$stmt->close();

// Crear una cláusula IN con los IDs de los eventos registrados
$registered_event_ids_str = implode(',', $registered_event_ids);
$exclude_clause = $registered_event_ids_str ? "AND id NOT IN ($registered_event_ids_str)" : "";

// Filtrar eventos
$where_clauses = [];
if (!empty($_GET['fecha'])) {
    $where_clauses[] = "fecha = '" . $conn->real_escape_string($_GET['fecha']) . "'";
}
if (!empty($_GET['lugar'])) {
    $where_clauses[] = "lugar = '" . $conn->real_escape_string($_GET['lugar']) . "'";
}
if (!empty($_GET['hora'])) {
    $where_clauses[] = "hora = '" . $conn->real_escape_string($_GET['hora']) . "'";
}
if (!empty($_GET['capacidad'])) {
    $where_clauses[] = "capacidad = " . intval($_GET['capacidad']);
}

$where_sql = implode(' AND ', $where_clauses);
if ($where_sql) {
    $where_sql = "WHERE $where_sql $exclude_clause";
} else {
    $where_sql = $exclude_clause ? "WHERE 1=1 $exclude_clause" : "";
}

$sql = "SELECT * FROM eventos $where_sql";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lista de Eventos</title>
  <link href="https://unpkg.com/tailwindcss@^2.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold text-gray-800">Lista de Eventos</h2>
    <form method="GET" class="mb-6">
      <div class="flex flex-wrap -mx-2">
        <div class="w-full md:w-1/4 px-2 mb-4">
          <label for="fecha" class="block text-gray-700">Fecha</label>
          <input type="date" name="fecha" id="fecha" class="w-full px-3 py-2 border rounded">
        </div>
        <div class="w-full md:w-1/4 px-2 mb-4">
          <label for="lugar" class="block text-gray-700">Lugar</label>
          <input type="text" name="lugar" id="lugar" class="w-full px-3 py-2 border rounded">
        </div>
        <div class="w-full md:w-1/4 px-2 mb-4">
          <label for="hora" class="block text-gray-700">Hora</label>
          <input type="time" name="hora" id="hora" class="w-full px-3 py-2 border rounded">
        </div>
        <div class="w-full md:w-1/4 px-2 mb-4">
          <label for="capacidad" class="block text-gray-700">Capacidad Mínima</label>
          <input type="number" name="capacidad" id="capacidad" class="w-full px-3 py-2 border rounded">
        </div>
      </div>
      <button type="submit" class="px-4 py-2 bg-yellow-600 text-white font-bold rounded hover:bg-yellow-700">Filtrar</button>
    </form>
    <table class="table-auto w-full">
      <thead>
        <tr class="bg-gray-100 text-gray-600 text-sm font-medium">
          <th class="px-6 py-3">Título</th>
          <th class="px-6 py-3">Descripción</th>
          <th class="px-6 py-3">Fecha</th>
          <th class="px-6 py-3">Hora</th>
          <th class="px-6 py-3">Lugar</th>
          <th class="px-6 py-3">Capacidad</th>
          <th class="px-6 py-3">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr class="border-b hover:bg-gray-100">
            <td class="px-6 py-4"><?php echo htmlspecialchars($row['titulo']); ?></td>
            <td class="px-6 py-4"><?php echo htmlspecialchars($row['descripcion']); ?></td>
            <td class="px-6 py-4"><?php echo htmlspecialchars($row['fecha']); ?></td>
            <td class="px-6 py-4"><?php echo htmlspecialchars($row['hora']); ?></td>
            <td class="px-6 py-4"><?php echo htmlspecialchars($row['lugar']); ?></td>
            <td class="px-6 py-4"><?php echo htmlspecialchars($row['capacidad']); ?></td>
            <td class="px-6 py-4 text-center">
              <a href="registrar_evento.php?id=<?php echo $row['id']; ?>" class="px-2 py-1 text-blue-600 font-bold rounded hover:bg-blue-100">Registrarse</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>

