<?php
// Include the database handler with the correct path
include('./DB_Functions/assets/database_handler.php');
$db_handler = new DB_Handler();




// Handle fetching players data for the JavaScript
if (isset($_GET['fetch_players'])) {
    echo $db_handler->handler("requestUsers");
    exit;
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tournament Bracket</title>
    <style>
        :root {
            --fifaMint: #07F468;
            --fifaGrey: #161616;
            --fifaWhiteSmoke: #f3f3f3;
        }




        body { font-family: Arial, sans-serif; color: var(--fifaWhiteSmoke); background-color: var(--fifaGrey); }
        .bracket { display: flex; flex-direction: column; align-items: center; color: var(--fifaWhiteSmoke); }
        .round { margin-top: 20px; display: flex; justify-content: space-around; }
        .match { display: flex; flex-direction: column; margin: 10px; padding: 1rem; border: 1px solid var(--fifaMint); border-radius: 8px; }
        .player { display: flex; justify-content: space-between; }
        .winner { font-weight: bold; color: var(--fifaMint); }
        input[type="number"] { font-family: Verdana; font-size: 20px; padding: 0.5rem; width: 100%; color: var(--fifaWhiteSmoke); background-color: var(--fifaGrey); border: solid 1px var(--fifaWhiteSmoke); border-radius: 0.5rem; }
        button { margin-top: 1rem; background-color: var(--fifaMint); width: 50%; border-radius: 2rem; border: solid 1px var(--fifaMint); padding: 0.5rem; color: var(--fifaGrey); font-weight: bold; cursor: pointer; }
        button:hover { font-weight: bold; background-color: var(--fifaWhiteSmoke); color: var(--fifaGrey); }
        #winner-display { margin-top: 30px; font-size: 1.5em; color: var(--fifaMint); text-align: center; }
    </style>
</head>
<body>
    <h1>Tournament Bracket</h1>
    <div id="bracket" class="bracket"></div>
    <div id="winner-display"></div> <!-- Winner announcement section with centered text -->




    <script>
        async function fetchPlayers() {
            const response = await fetch('?fetch_players=1');
            return await response.json();
        }




        let rounds = [];
        let tournamentCompleted = false;
        let processedRounds = new Set(); // Track rounds that have already been processed




        function createBracket(players) {
            let round = players.map((player, index) => ({
                player1: player,
                player2: players[index + 1] || null,
                winner: null,
                score1: 0,
                score2: 0,
            })).filter((match, index) => index % 2 === 0);




            rounds.push(round);
            renderBracket();
        }




        function renderBracket() {
            const bracketContainer = document.getElementById('bracket');
            bracketContainer.innerHTML = '';




            rounds.forEach((round, roundIndex) => {
                renderRound(round, bracketContainer, roundIndex);
            });




            if (tournamentCompleted) {
                const winner = rounds[rounds.length - 1][0].winner;
                document.getElementById('winner-display').innerHTML = `Winner: ${winner.player_name} ${winner.player_lname}`;
            }
        }




        function renderRound(round, container, roundIndex) {
            const roundDiv = document.createElement('div');
            roundDiv.classList.add('round');
            roundDiv.innerHTML = `<h2>Round ${roundIndex + 1}</h2>`;
            container.appendChild(roundDiv);




            round.forEach((match, matchIndex) => {
                const matchDiv = document.createElement('div');
                matchDiv.classList.add('match');
                matchDiv.innerHTML = `
                    <div class="player">
                        <span>${match.player1.player_name} ${match.player1.player_lname}</span>
                        <input type="number" value="${match.score1}" min="0" onchange="updateScore(${roundIndex}, ${matchIndex}, 1, this)" ${match.player2 ? '' : 'disabled'}>
                    </div>
                    ${match.player2 ? `
                    <div class="player">
                        <span>${match.player2.player_name} ${match.player2.player_lname}</span>
                        <input type="number" value="${match.score2}" min="0" onchange="updateScore(${roundIndex}, ${matchIndex}, 2, this)">
                    </div>` : '<div class="winner">No Opponent - Advances</div>'}
                `;
                roundDiv.appendChild(matchDiv);
            });




            const submitButton = document.createElement('button');
            submitButton.textContent = "Submit Scores";
            submitButton.id = `submit-round-${roundIndex}`;  // Unique ID for each round's button
            submitButton.onclick = () => submitScores(roundIndex);
            roundDiv.appendChild(submitButton);




            // Disable submit button if round has already been processed
            if (tournamentCompleted || processedRounds.has(roundIndex)) {
                submitButton.disabled = true;
            }
        }




        function updateScore(roundIndex, matchIndex, playerNum, input) {
            const score = parseInt(input.value, 10);
            if (score >= 0) rounds[roundIndex][matchIndex][`score${playerNum}`] = score;
        }




        function submitScores(roundIndex) {
    if (processedRounds.has(roundIndex)) return; // Prevent re-processing a completed round
    processedRounds.add(roundIndex); // Mark round as processed


    const submitButton = document.getElementById(`submit-round-${roundIndex}`);
    submitButton.disabled = true; // Disable the button immediately upon submitting


    const currentRound = rounds[roundIndex];
    const nextRound = [];
    let tieDetected = false;


    currentRound.forEach((match) => {
        if (match.player2 === null || match.score1 > match.score2) {
            match.winner = match.player1;
        } else if (match.score2 > match.score1) {
            match.winner = match.player2;
        } else {
            alert("Tie detected; please ensure one player has a higher score.");
            tieDetected = true;
            processedRounds.delete(roundIndex); // Remove from processed if a tie is detected
            submitButton.disabled = false; // Re-enable if a tie is detected
            return;
        }


        if (!tieDetected) {
            const matchIndex = Math.floor(currentRound.indexOf(match) / 2);
            const nextMatch = nextRound[matchIndex] || { player1: null, player2: null, score1: 0, score2: 0 };


            if (currentRound.indexOf(match) % 2 === 0) {
                nextMatch.player1 = match.winner;
            } else {
                nextMatch.player2 = match.winner;
            }


            if (!nextRound[matchIndex]) {
                nextRound[matchIndex] = nextMatch;
            }
        }
    });


    if (tieDetected) return;


    // Check if the tournament is completed
    if (nextRound.length === 1 && !nextRound[0].player2) {
        const winner = nextRound[0].player1;
        tournamentCompleted = true;
        document.getElementById('winner-display').innerHTML = `Winner: ${winner.player_name} ${winner.player_lname}`;
    } else if (nextRound.length > 0) {
        // Initialize scores for the new round
        nextRound.forEach((match) => {
            match.score1 = 0;
            match.score2 = 0;
        });


        rounds.push(nextRound);
        renderBracket();
    }
}
        fetchPlayers().then(players => createBracket(players));
    </script>
</body>
</html>



