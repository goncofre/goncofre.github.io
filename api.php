<?php
header('Content-Type: application/json; charset=utf-8');

$league = $_GET['league'];

$api_url = 'https://api.football-data.org/v4/competitions/'.$league.'/standings';
$api_token = '95411b80d0d54248a859a01061f60d73';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$headers = [
    'X-Auth-Token: ' . $api_token,
];
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);


if (curl_errno($ch)) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de cURL: ' . curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);

if ($http_code !== 200) {
    http_response_code($http_code);
    echo $response;
    exit;
}

$data = json_decode($response, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al decodificar la respuesta JSON de la API.']);
    exit;
}

if (isset($data['standings'])) {
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} else {
    http_response_code(502);
    echo json_encode(['error' => 'La respuesta de la API no contiene los datos esperados.', 'api_response' => $data]);
}
?>

<script>
    const API_TOKEN = '95411b80d0d54248a859a01061f60d73';
    const API_URL = 'https://api.football-data.org/v4/competitions/PL/standings';

    async function fetchLeagueStandings() {
        try {
            const response = await fetch(API_URL, {
                method: 'GET',
                headers: {
                    'X-Auth-Token': API_TOKEN
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
        
            console.log('API data:', data);

            displayData(data);

        } catch (error) {
            console.error('Error fetching data:', error);
        }
    }

    function displayData(data) {
        if (!data.standings || data.standings.length === 0) {
            console.log('No standings data found.');
            return;
        }

        const standingsTable = data.standings[0].table;
        const tableContainer = document.getElementById('standings-table-container');

        let html = `<h2>${data.competition.name} Standings</h2>`;
        html += '<table border="1" style="border-collapse: collapse; width: 100%;">';
        html += '<thead><tr><th>Pos</th><th>Team</th><th>Pts</th><th>Played</th><th>Won</th><th>Drawn</th><th>Lost</th></tr></thead>';
        html += '<tbody>';

        standingsTable.forEach(team => {
            html += `
                <tr>
                    <td>${team.position}</td>
                    <td><img src="${team.team.crest}" alt="${team.team.name}" width="20" height="20"> ${team.team.name}</td>
                    <td>${team.points}</td>
                    <td>${team.playedGames}</td>
                    <td>${team.won}</td>
                    <td>${team.draw}</td>
                    <td>${team.lost}</td>
                </tr>
            `;
        });

        html += '</tbody></table>';
        tableContainer.innerHTML = html;
    }

    fetchLeagueStandings();
</script>