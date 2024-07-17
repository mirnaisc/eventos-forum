<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PRINCIPAL</title>
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
    }

    .transparent-container {
      background-color: rgba(255, 255, 255, 0.8); /* Blanco con 80% de opacidad */
    }
  </style>
</head>
<body>
  <div class="container mx-auto px-4 py-8">
    <div class="transparent-container border border-gray-200 rounded-lg p-8 max-w-md mx-auto shadow-lg">
      <div class="text-center">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">BIENVENIDO A FORUM</h2>
        <div class="flex justify-center space-x-4">
          <a href="registro.php">
            <button class="px-4 py-2 bg-yellow-600 text-white font-bold rounded-full hover:bg-yellow-700 transition duration-300">Registrarse</button>
          </a>
          <a href="login.php">
            <button class="px-4 py-2 bg-gray-300 text-gray-700 font-bold rounded-full hover:bg-gray-400 transition duration-300">Iniciar Sesión</button>
          </a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
