<?php
session_start();
include 'config.php';
require 'Composer/index.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario_id = $_SESSION['user_id'];
    $evento_id = $_POST['evento_id'];

    $sql = "INSERT INTO registros (usuario_id, evento_id) VALUES ('$usuario_id', '$evento_id')";
    $result = $conn->query("SELECT email FROM usuarios WHERE id = '$usuario_id'");
    $row = $result->fetch_assoc();
    $correoAsistente = $row['email'];
    $asuntoEmail = 'Registro a evento exitoso';
    $bodyEmail = 'Estimado asistente, su registro al evento ha sido exitoso, para más información ingrese a la plataforma.';

    if ($conn->query($sql) === TRUE) {
        $resultadoCorreo = enviarCorreo($asuntoEmail, $bodyEmail, $correoAsistente);
        echo "<script>alert('$resultadoCorreo');</script>";
        //echo "Registro exitoso";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
}

if (isset($_GET['id'])) {
    $evento_id = $_GET['id'];
    $sql = "SELECT * FROM eventos WHERE id='$evento_id'";
    $result = $conn->query($sql);
    $evento = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro a Evento</title>
  <link href="https://unpkg.com/tailwindcss@^2.0/dist/tailwind.min.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f5f5f5;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
    }

    .container {
      max-width: 500px;
      width: 100%;
      background-color: #fff;
      border: 1px solid #ccc;
      border-radius: 8px;
      padding: 40px 30px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: transform 0.2s ease-in-out;
    }

    .container:hover {
      transform: translateY(-10px);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }

    h3 {
      text-align: center;
      margin-bottom: 20px;
      color: #666;
    }

    form {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    button {
      background-color: #4CAF50;
      color: #fff;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #45a049;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Registro a Evento</h2>
    <h3><?php echo htmlspecialchars($evento['titulo']); ?></h3>
    <form method="POST" action="">
      <input type="hidden" name="evento_id" value="<?php echo htmlspecialchars($evento['id']); ?>">
      <button type="submit">Registrarse</button>
    </form>
  </div>
</body>
</html>
