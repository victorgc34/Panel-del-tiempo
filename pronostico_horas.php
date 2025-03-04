<?php
include 'funciones/funciones.php';

//verifica si las variables están definidas
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

//Si las variables existen
if ($lat && $lon) {
    //Almacena el pronostico por horas de la temperatura
    $pronosticot = obtener_pronostico_horas($lat, $lon);
    //Almacena el pronostico por horas de la lluvia
    $pronosticolluvia = obtener_pronostico_lluvia_horas($lat, $lon);
} else {
    echo "Dirección invalida";
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pronostico por horas - <?php echo htmlspecialchars($city); ?></title>
    <link rel="stylesheet" href="recursos/css/styles.css">
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <style>
        #chart-container {
            width: 100%;
            height: 400px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Pronóstico por horas en <?php echo htmlspecialchars($city); ?></h1>
        <?php if ($pronosticot): ?>
            <div id="hourlyChart"></div>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const hourlyData = <?php echo json_encode($pronosticot); ?>;
                    const labels = hourlyData.map(entry => entry.time);
                    const temps = hourlyData.map(entry => entry.temp);

                    Highcharts.chart('hourlyChart', {
                        title: {
                            text: 'Pronóstico de tempuratura por horas'
                        },
                        xAxis: {
                            categories: labels
                            
                        },
                        yAxis: {
                            title: {
                                text: 'Temperatura (°C)'
                            }
                        },
                        series: [{
                            name: 'Temperatura',
                            data: temps,
                            //En este apartado, podemos cambiar todo el color de la grafica
                            color: '#FF0000'
                        }],
                        responsive: {
                            rules: [{
                                condition: {
                                    maxWidth: 500
                                },
                                chartOptions: {
                                    legend: {
                                        layout: 'horizontal',
                                        align: 'center',
                                        verticalAlign: 'bottom'
                                    }
                                }
                            }]
                        }
                    });
                });
            </script>
        <?php else: ?>
            <p>No se han recibido datos del pronóstico</p>
        <?php endif; ?>
        <!--Apartado de la lluvia-->
        <?php if ($pronosticolluvia): ?>
            <div id="chart-container"></div>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Highcharts.chart('chart-container', {
                        chart: {
                            type: 'column',
                            backgroundColor: 'rgba(255, 255, 255, 0.2)'
                        },
                        title: {
                            text: 'Lluvia por horas (Próximas 48 Horas)'
                        },
                        xAxis: {
                            categories: [<?php echo implode(',', array_map(function($hour) { return "'" . $hour['time'] . "'"; }, $pronosticolluvia)); ?>],
                            labels: {
                                style: {
                                    color: '#333333'
                                },
                                rotation: -45 
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'Lluvia (mm)',
                                style: {
                                    color: '#333333'
                                }
                            },
                            labels: {
                                style: {
                                    color: '#333333'
                                }
                            }
                        },
                        tooltip: {
                            shared: true,
                            valueSuffix: ' mm'
                        },
                        series: [{
                            name: 'Lluvia',
                            data: [<?php echo implode(',', array_map(function($hour) { return $hour['rain']; }, $pronosticolluvia)); ?>],
                            color: '#007BFF',
                            tooltip: {
                                valueDecimals: 2
                            }
                        }],
                        legend: {
                            itemStyle: {
                                color: '#333333'
                            }
                        },
                        credits: {
                            enabled: false
                        }
                    });
                });
            </script>
        <?php else: ?>
            <p>No se pudo obtener el pronóstico de lluvia.</p>
        <?php endif; ?>
        <button type="button" class="btn btn-primary btn-sm">
            <a style="color: white; text-decoration: none;" href="javascript:history.back()">
                Volver
            </a>
        </button>
    </div>
</body>
</html>