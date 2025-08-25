<?php
$api_url = 'https://api.football-data.org/v4/persons/4832';
$api_token = '95411b80d0d54248a859a01061f60d73';

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$headers = [
    'X-Auth-Token: ' . $api_token,
];
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error de cURL: ' . curl_error($ch);
} else {
    $data = json_decode($response, true);

    if (isset($data['name'])) {
        $player_name = $data['name'];
        $player_position = $data['position'] ?? 'No disponible';
        $shirt_number = $data['shirtNumber'] ?? 'No disponible';
        $birth_date = $data['dateOfBirth'] ?? 'No disponible';
        $nationality = $data['nationality'] ?? 'No disponible';
        $team_name = $data['currentTeam']['name'] ?? 'Sin equipo';

        echo '<h1>Perfil del Jugador: ' . $player_name . '</h1>';
        echo '<div style="border: 1px solid #ccc; padding: 20px; margin-top: 20px;">';
        echo '<p><strong>Posición:</strong> ' . $player_position . '</p>';
        echo '<p><strong>Número de camiseta:</strong> ' . $shirt_number . '</p>';
        echo '<p><strong>Equipo actual:</strong> ' . $team_name . '</p>';
        echo '<p><strong>Fecha de nacimiento:</strong> ' . $birth_date . '</p>';
        echo '<p><strong>Nacionalidad:</strong> ' . $nationality . '</p>';
        echo '</div>';

    } else {
        echo 'Error: No se encontró el jugador. La respuesta de la API fue:';
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}


curl_close($ch);
?>