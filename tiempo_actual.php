<?php
include 'funciones/funciones.php';

//verifica si las variables están definidas y obtenerlas
if (isset($_GET['lat'])) {
    $lat = $_GET['lat'];
} else {
    $lat = '';
}

if (isset($_GET['lon'])) {
    $lon = $_GET['lon'];
} else {
    $lon = '';
}

if (isset($_GET['city'])) {
    $city = $_GET['city'];
} else {
    $city = '';
}

// Obtener el tiempo actual
$tiempo_actual = obtener_tiempo_actual($lat, $lon);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiempo Actual en <?php echo htmlspecialchars($city); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: rgba(255, 255, 255, 0.2);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 80%;
            max-width: 600px;
        }
        h1 {
            margin-bottom: 20px;
            font-size: 2.5em;
            text-align: center;
        }
        .weather-info {
            font-size: 1.2em;
            margin: 10px 0;
        }
        .weather-description {
            font-size: 1.5em;
            margin: 20px 0;
        }
        .links {
            margin-top: 20px;
        }
        .links a {
            text-decoration: none;
            color: #fff;
            background: #007BFF;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 5px;
            display: inline-block;
            transition: background 0.3s ease;
        }
        .links a:hover {
            background: #0056b3;
        }
        .back-button {
            text-decoration: none;
            color: #fff;
            background: #28a745;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
            display: inline-block;
            transition: background 0.3s ease;
        }
        .back-button:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tiempo Actual en <?php echo htmlspecialchars($city); ?></h1>
        <?php if ($tiempo_actual): ?>
            <div class="weather-info">Temperatura: <?php echo $tiempo_actual['temp']; ?>°C</div>
            <div class="weather-info">Humedad: <?php echo $tiempo_actual['humidity']; ?>%</div>
            <div class="weather-info">Lluvia: <?php echo $tiempo_actual['rain']; ?> mm</div>
        <?php else: ?>
            <p>No se pudo obtener el tiempo actual.</p>
        <?php endif; ?>
        <div class="links">
            <a href="pronostico_horas.php?lat=<?php echo $lat; ?>&lon=<?php echo $lon; ?>&city=<?php echo $city; ?>">Pronóstico 48 horas</a>
            <a href="pronostico_semana.php?lat=<?php echo $lat; ?>&lon=<?php echo $lon; ?>&city=<?php echo $city; ?>">Pronóstico 5 dias</a>
        </div>
        <a href="index.php" class="back-button">Volver</a>
    </div>
</body>
</html>
