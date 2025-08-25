<?php
$api_url = 'https://api.football-data.org/v4/competitions/PL/teams';
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

    if (isset($data['teams']) && !empty($data['teams'])) {
        echo '<h1>Equipos de la Premier League</h1>';
        
        $teams = $data['teams'];

        foreach ($teams as $team) {
            echo '<div style="border: 1px solid #ccc; padding: 15px; margin-bottom: 20px;">';
            
            echo '<h2>' . $team['name'] . '</h2>';
            if (!empty($team['crest'])) {
                echo '<img src="' . $team['crest'] . '" alt="Escudo de ' . $team['name'] . '" width="50">';
            }
            
            $coach_name = 'No disponible';
            if (!empty($team['coach']['name'])) {
                $coach_name = $team['coach']['name'];
            }
            echo '<p><strong>Entrenador:</strong> ' . $coach_name . '</p>';
            
            if (!empty($team['squad'])) {
                echo '<h3>Jugadores</h3>';
                echo '<ul style="list-style-type: none; padding: 0;">';
                
                //Limita el número de jugadores mostrados para no hacer la página muy larga
                //$squad_limit = 10;
                $count = 0;
                
                foreach ($team['squad'] as $player) {
                    /*if ($count >= $squad_limit) {
                        echo '<li>...y ' . (count($team['squad']) - $squad_limit) . ' jugadores más.</li>';
                        break;
                    }*/
                    echo '<li>' . $player['name'] . '</li>';
                    $count++;
                }
                echo '</ul>';
            } else {
                echo '<p>No se encontraron jugadores para este equipo.</p>';
            }

            echo '</div>';
        }
    } else {
        echo 'Error: No se encontraron equipos. La respuesta de la API fue:';
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}

curl_close($ch);
?>