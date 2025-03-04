<?php
include 'funciones/funciones.php';

if (isset($_GET['ciudad'])) {
    $city = $_GET['ciudad'];
} else {
    $city = '';
}
// El $city es lo mismo que !empty($city)
if ($city) {
    $location = obtener_localizacion_ciudad($city);
    //!empty($localtion)
    if ($location) {
        //Esta linea la utilizo para redirigir a los usuarios con los parametros de latitud, longuitud ...
        header("Location: tiempo_actual.php?lat={$location['lat']}&lon={$location['lon']}&city={$city}");
    } else {
        echo "Ciudad no encontrada";
    }
} else {
    echo "Introduce una ciudad";
}
?>