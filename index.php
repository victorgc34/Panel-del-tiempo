<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplicación del Tiempo</title>
    <link rel="stylesheet" href="recursos/css/styles.css">
    <script src="assets/js/chart.min.js"></script>
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
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        input[type="text"] {
            padding: 10px;
            font-size: 1em;
            border: none;
            border-radius: 5px;
            margin-bottom: 20px;
            width: 80%;
            max-width: 400px;
        }
        button {
            background: #007BFF;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Aplicación del Tiempo</h1>
        <form action="ciudad-cordenadas.php" method="get">
            <input type="text" name="ciudad" placeholder="Introduce una ciudad:" required>
            <button type="submit">Buscar</button>
        </form>
    </div>
</body>
</html>