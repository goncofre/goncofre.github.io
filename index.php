<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Posiciones de Ligas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto p-8">
        <h1 class="text-4xl font-bold text-center text-gray-800 mb-8">Tabla de Posiciones de Ligas de Fútbol</h1>

        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-700">Seleccionar Liga</h2>
            <select id="league-select" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150">
                <option value="" disabled selected>-- Elige una liga --</option>
                <option value="BL1">Bundesliga</option>
                <option value="DED">Eredivisie</option>
                <option value="BSA">Campeonato Brasileiro Série A</option>
                <option value="PD">Primera Division Española</option>
                <option value="FL1">Ligue 1</option>
                <option value="ELC">Championship Inglesa</option>
                <option value="PPL">Primeira Liga Portugal</option>
                <option value="SA">Serie A</option>
                <option value="PL">Premier League</option>
            </select>
        </div>

        <div id="results-container" class="bg-white p-6 rounded-lg shadow-md min-h-[300px] flex items-center justify-center">
            <p class="text-gray-500">Selecciona una liga para ver la tabla de posiciones.</p>
        </div>
        <div id="scores-container" class="bg-white p-6 rounded-lg shadow-md min-h-[300px] flex items-center justify-center">
            <p class="text-gray-500">Selecciona una liga para ver la tabla de goleadores.</p>
        </div>
    </div>

    <script>
        const leagueSelect = document.getElementById('league-select');
        const resultsContainer = document.getElementById('results-container');
        const scoresContainer = document.getElementById('scores-container');

        leagueSelect.addEventListener('change', (event) => {
            const selectedLeague = event.target.value;
            
            resultsContainer.innerHTML = '<p class="text-gray-500 animate-pulse">Cargando datos...</p>';
            scoresContainer.innerHTML = '<p class="text-gray-500 animate-pulse">Cargando datos...</p>';

            fetchLeagueData(selectedLeague);
            fetchScoreData(selectedLeague);
        });

        async function fetchLeagueData(leagueCode) {
            try {
                const response = await fetch(`api.php?league=${leagueCode}`);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                displayStandings(data);
            } catch (error) {
                resultsContainer.innerHTML = `<p class="text-red-500">Error al cargar los datos: ${error.message}. Por favor, inténtalo de nuevo más tarde.</p>`;
            }
        }

        async function fetchScoreData(leagueCode) {
            try {
                const response = await fetch(`goleadores.php?league=${leagueCode}`);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                displayScore(data);
            } catch (error) {
                scoresContainer.innerHTML = `<p class="text-red-500">Error al cargar los datos: ${error.message}. Por favor, inténtalo de nuevo más tarde.</p>`;
            }
        }

        function displayStandings(data) {
            resultsContainer.innerHTML = '';

            if (data.standings && data.standings.length > 0) {
                const standings = data.standings[0].table;

                let tableHtml = `<table class="min-w-full bg-white border border-gray-200">`;
                tableHtml += `<thead><tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">`;
                tableHtml += `<th class="py-3 px-6 text-left">Pos</th>`;
                tableHtml += `<th class="py-3 px-6 text-left">Equipo</th>`;
                tableHtml += `<th class="py-3 px-6 text-left">PJ</th>`;
                tableHtml += `<th class="py-3 px-6 text-left">G</th>`;
                tableHtml += `<th class="py-3 px-6 text-left">E</th>`;
                tableHtml += `<th class="py-3 px-6 text-left">P</th>`;
                tableHtml += `<th class="py-3 px-6 text-left">GF</th>`;
                tableHtml += `<th class="py-3 px-6 text-left">GC</th>`;
                tableHtml += `<th class="py-3 px-6 text-left">Dif</th>`;
                tableHtml += `<th class="py-3 px-6 text-left">Pts</th>`;
                tableHtml += `</tr></thead><tbody class="text-gray-600 text-sm font-light">`;

                standings.forEach(team => {
                    tableHtml += `<tr class="border-b border-gray-200 hover:bg-gray-100">`;
                    tableHtml += `<td class="py-3 px-6 text-left whitespace-nowrap">${team.position}</td>`;
                    tableHtml += `<td class="py-3 px-6 text-left flex items-center"><img src="${team.team.crest}" alt="${team.team.name}" class="w-6 h-6 mr-2"> ${team.team.name}</td>`;
                    tableHtml += `<td class="py-3 px-6 text-left">${team.playedGames}</td>`;
                    tableHtml += `<td class="py-3 px-6 text-left">${team.won}</td>`;
                    tableHtml += `<td class="py-3 px-6 text-left">${team.draw}</td>`;
                    tableHtml += `<td class="py-3 px-6 text-left">${team.lost}</td>`;
                    tableHtml += `<td class="py-3 px-6 text-left">${team.goalsFor}</td>`;
                    tableHtml += `<td class="py-3 px-6 text-left">${team.goalsAgainst}</td>`;
                    tableHtml += `<td class="py-3 px-6 text-left">${team.goalDifference}</td>`;
                    tableHtml += `<td class="py-3 px-6 text-left font-bold">${team.points}</td>`;
                    tableHtml += `</tr>`;
                });

                tableHtml += `</tbody></table>`;
                resultsContainer.innerHTML = tableHtml;

            } else {
                resultsContainer.innerHTML = `<p class="text-gray-500">No se encontraron datos para la liga seleccionada.</p>`;
            }
        }

         function displayScore(data) {
            scoresContainer.innerHTML = '';

            if (data.scorers && data.scorers.length > 0) {
                const scorers = data.scorers;

                let tableHtml = `<table class="min-w-full bg-white border border-gray-200">`;
                tableHtml += `<thead><tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">`;
                tableHtml += `<th class="py-3 px-6 text-left">Jugador</th>`;
                tableHtml += `<th class="py-3 px-6 text-left">Equipo</th>`;
                tableHtml += `<th class="py-3 px-6 text-left">Goles</th>`;
                tableHtml += `</tr></thead><tbody class="text-gray-600 text-sm font-light">`;

                for(i=0;i<scorers.length;i++){
                    tableHtml += `<tr class="border-b border-gray-200 hover:bg-gray-100">`;
                    tableHtml += `<td class="py-3 px-6 text-left">${scorers[i].player.firstName} ${scorers[i].player.lastName}</td>`;
                    tableHtml += `<td class="py-3 px-6 text-left">${scorers[i].team.name}</td>`;
                    tableHtml += `<td class="py-3 px-6 text-left">${scorers[i].goals}</td>`;
                    
                    tableHtml += `</tr>`;
                }

                scoresContainer.innerHTML = tableHtml;

            } else {
                scoresContainer.innerHTML = `<p class="text-gray-500">No se encontraron datos para la liga seleccionada.</p>`;
            }
        }
    </script>
</body>
</html>