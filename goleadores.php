<?php
header('Content-Type: application/json; charset=utf-8');

if (empty($_GET['league'])) {
    http_response_code(400);
    echo json_encode(['error' => 'El parámetro "league" es obligatorio.']);
    exit;
}
$league = $_GET['league'];

$api_url = 'https://api.football-data.org/v4/competitions/'.$league.'/scorers';
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

if (isset($data['scorers'])) {
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} else {
    http_response_code(502);
    echo json_encode(['error' => 'La respuesta de la API no contiene los datos esperados.', 'api_response' => $data]);
}
?>