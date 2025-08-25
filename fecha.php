<?php
$api_url = 'https://api.football-data.org/v4/competitions/PL/matches?matchday=2';
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

    if (isset($data['matches']) && !empty($data['matches'])) {
        echo '<h1>Partidos - Premier League | Jornada 2</h1>';
        
        $matches = $data['matches'];

        echo '<table border="1" cellpadding="10" cellspacing="0">';
        echo '<thead><tr><th>Fecha y Hora</th><th>Local</th><th></th><th>Visitante</th><th>Marcador</th><th>Estado</th></tr></thead>';
        echo '<tbody>';
        
        foreach ($matches as $match) {
            $match_date = new DateTime($match['utcDate']);
            $formatted_date = $match_date->format('d-m-Y H:i');

            $home_team_name = $match['homeTeam']['name'];
            $away_team_name = $match['awayTeam']['name'];
            $status = $match['status'];

            $score = '-';
            if ($status === 'FINISHED') {
                $score = $match['score']['fullTime']['home'] . ' - ' . $match['score']['fullTime']['away'];
            }

            echo '<tr>';
            echo '<td>' . $formatted_date . '</td>';
            echo '<td>' . $home_team_name . '</td>';
            echo '<td>vs</td>';
            echo '<td>' . $away_team_name . '</td>';
            echo '<td>' . $score . '</td>';
            echo '<td>' . $status . '</td>';
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';

    } else {
        echo 'Error: No se encontraron partidos para la jornada especificada. La respuesta de la API fue:';
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}

curl_close($ch);
?>