<?php
function obtener_localizacion_ciudad($city) {
    $apiKey = 'aa3d2251a838cbea1b00cef7369b7a2e';
    $url = "http://api.openweathermap.org/geo/1.0/direct?q={$city}&limit=1&appid={$apiKey}";

    $response = @file_get_contents($url);

    $data = json_decode($response, true);

    if (!empty($data)) {
        return [
            'lat' => $data[0]['lat'],
            'lon' => $data[0]['lon']
        ];
    }

    return null;
}

function obtener_tiempo_actual($lat, $lon) {
    $apiKey = 'aa3d2251a838cbea1b00cef7369b7a2e';
    $url = "https://api.openweathermap.org/data/2.5/weather?lat={$lat}&lon={$lon}&units=metric&appid={$apiKey}";

    $response = @file_get_contents($url);

    $data = json_decode($response, true);
    //El cod 200 se utiliza para identificar que la respuesta es valida. Tambien comprueba si data existe
    if ($data && $data['cod'] == 200) {
        return [
            'temp' => $data['main']['temp'],
            'humidity' => $data['main']['humidity'],
            'description' => $data['weather'][0]['description'],
            'rain' => isset($data['rain']['1h']) ? $data['rain']['1h'] : 0,
        ];
    }

    return null;
}

function obtener_pronostico_horas($lat, $lon) {
    $apiKey = 'aa3d2251a838cbea1b00cef7369b7a2e';
    $url = "https://api.openweathermap.org/data/2.5/forecast?lat={$lat}&lon={$lon}&units=metric&appid={$apiKey}";

    $response = @file_get_contents($url);

    $data = json_decode($response, true);

    if ($data && $data['cod'] == '200') {
        $forecast = [];
        for ($i = 0; $i < 16; $i++) { //Para mostrar las 48 horas proximas el bucle se tiene que repetir hasta
            $hour = $data['list'][$i];
            $forecast[] = [
                'time' => $hour['dt_txt'],
                'temp' => $hour['main']['temp']
            ];
        }
        return $forecast;
    }

    return null;
}

function obtener_pronostico_lluvia_horas($lat, $lon) {
    $apiKey = 'aa3d2251a838cbea1b00cef7369b7a2e';
    $url = "https://api.openweathermap.org/data/2.5/forecast?lat={$lat}&lon={$lon}&units=metric&appid={$apiKey}";

    $response = @file_get_contents($url);

    $data = json_decode($response, true);

    if ($data && $data['cod'] == '200') {
        $forecast1 = [];
        for ($i = 0; $i < 16; $i++) { 
            $hour = $data['list'][$i];
            $forecast1[] = [
                'time' => $hour['dt_txt'],
                //Lo siguiente comprueba si existe dentro de la apartado [rain] el apartado de [3h], si no existe, lo que hará será añadirle 0
                'rain' => isset($hour['rain']['3h']) ? $hour['rain']['3h'] : 0
            ];
        }
        return $forecast1;
    }

    return null;
}

function obtener_pronostico_diario($lat, $lon, $days = 5) {
    $apiKey = 'aa3d2251a838cbea1b00cef7369b7a2e';
    $url = "https://api.openweathermap.org/data/2.5/forecast?lat={$lat}&lon={$lon}&units=metric&appid={$apiKey}";

    $response = @file_get_contents($url);

    $data = json_decode($response, true);

    if ($data && $data['cod'] == '200') {
        $forecast = [];
        $dates = [];
        foreach ($data['list'] as $entry) {
            $date = date('Y-m-d', strtotime($entry['dt_txt']));
            if (!in_array($date, $dates) && count($dates) < $days) {
                $dates[] = $date;
                $forecast[] = [
                    'date' => $date,
                    'temp' => $entry['main']['temp']
                ];
            }
        }
        return $forecast;
    }
    return null;
}

function obtener_pronostico_lluvia_diario($lat, $lon, $days = 5) {
    $apiKey = 'aa3d2251a838cbea1b00cef7369b7a2e';
    $url = "https://api.openweathermap.org/data/2.5/forecast?lat={$lat}&lon={$lon}&units=metric&appid={$apiKey}";

    $response = @file_get_contents($url);

    $data = json_decode($response, true);

    if ($data && $data['cod'] == '200') {
        $forecast1 = [];
        $dates = [];
        $rainfall = [];
        foreach ($data['list'] as $entry) {
            $date = date('Y-m-d', strtotime($entry['dt_txt']));
            if (!isset($rainfall[$date])) {
                $rainfall[$date] = 0;
            }
            $rainfall[$date] += isset($entry['rain']['3h']) ? $entry['rain']['3h'] : 0;
        }

        $count = 0;
        foreach ($rainfall as $date => $rain) {
            if ($count >= $days) break;
            $forecast1[] = [
                'date' => $date,
                'rain' => $rain
            ];
            $count++;
        }

        return $forecast1;
    }
    return null;
}
?>