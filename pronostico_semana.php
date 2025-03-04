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

if ($lat && $lon) {
    $pronosticot = obtener_pronostico_diario($lat, $lon, 5);
    // Obtener el pronóstico de lluvia diario
    $pronosticolluvia = obtener_pronostico_lluvia_diario($lat, $lon, 5);
} else {
    echo "Direccion invalida";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pronóstico de <?php echo htmlspecialchars($city); ?> en los proximos dias</title>
    <link rel="stylesheet" href="recursos/css/styles.css">
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

</head>
<body>
    <div class="container">
        <h1 style="text-align: center;">Pronóstico de los proximos dias de <?php echo htmlspecialchars($city); ?></h1>
        <?php if ($pronosticot): ?>
            <div id="weeklyChart"></div>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const weeklyData = <?php echo json_encode($pronosticot); ?>;
                    const labels = weeklyData.map(entry => entry.date);
                    const temps = weeklyData.map(entry => entry.temp);

                    Highcharts.chart('weeklyChart', {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'Temperatura por dias'
                        },
                        xAxis: {
                            categories: labels,
                            crosshair: true
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: 'Temperatura (°C)'
                            }
                        },
                        tooltip: {
                            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                '<td style="padding:0"><b>{point.y:.1f} °C</b></td></tr>',
                            footerFormat: '</table>',
                            shared: true,
                            useHTML: true
                        },
                        plotOptions: {
                            column: {
                                pointPadding: 0.2,
                                borderWidth: 0
                            }
                        },
                        series: [{
                            name: 'Temperatura',
                            data: temps,
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
            <p>No se han recibido datos de temperatura.</p>
        <?php endif; ?>
        <!--Lluvia-->
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
                            text: 'Lluvia por días',
                            style: {
                                color: '#000'
                            }
                        },
                        xAxis: {
                            categories: [<?php echo implode(',', array_map(function($day) { return "'" . $day['date'] . "'"; }, $pronosticolluvia)); ?>],
                            labels: {
                                style: {
                                    color: '#333333' // Cambiar a un tono de gris oscuro
                                },
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
                            },
                            gridLineColor: 'rgba(255, 255, 255, 0.3)' // Ajustar color de las líneas de la cuadrícula
                        },
                        tooltip: {
                            shared: true,
                            valueSuffix: ' mm'
                        },
                        series: [{
                            name: 'Lluvia',
                            data: [<?php echo implode(',', array_map(function($day) { return $day['rain']; }, $pronosticolluvia)); ?>],
                            color: '#007BFF',
                            tooltip: {
                                valueDecimals: 2,
                                valueSuffix: ' mm'
                            },
                            visible: true // Asegurar que la serie de lluvia esté visible
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
        
    </div>
</body>
</html>