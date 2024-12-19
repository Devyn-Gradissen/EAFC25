<?php
include('./DB_Functions/assets/database_handler.php');
$db_handler = new DB_Handler();

// Handle AJAX requests to fetch player data
if (isset($_GET['fetch_players'])) {
    echo $db_handler->handler("requestUsers");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Left-Right Bracket Tournament</title>
    <style>
        :root {
            --fifaMint: #07F468;
            --fifaGrey: #161616;
            --fifaWhiteSmoke: #f3f3f3;
        }
        body {
            font-family: Arial, sans-serif;
            color: var(--fifaWhiteSmoke);
            background-color: var(--fifaGrey);
        }
        .tree-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            gap: 20px;
        }
        .bracket {
            display: flex;
            flex-direction: row;
            gap: 40px;
        }
        .round {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .match {
            display: flex;
            flex-direction: column;
            margin: 10px 0;
            padding: 0.5rem;
            border: 1px solid var(--fifaMint);
            border-radius: 8px;
            width: 150px;
            text-align: center;
        }
        .player {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
        }
        input[type="number"] {
            width: 60px;
            padding: 0.3rem;
            background-color: var(--fifaGrey);
            color: var(--fifaWhiteSmoke);
            border: 1px solid var(--fifaWhiteSmoke);
            border-radius: 0.3rem;
        }
        button {
            margin-top: 0.5rem;
            background-color: var(--fifaMint);
            color: var(--fifaGrey);
            padding: 0.3rem;
            font-size: 0.8rem;
            font-weight: bold;
            border: none;
            border-radius: 1rem;
            cursor: pointer;
        }
        button:hover {
            background-color: var(--fifaWhiteSmoke);
        }
        #winner-display {
            margin-top: 20px;
            font-size: 1.2em;
            text-align: center;
            color: var(--fifaMint);
        }
        #final-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        #final-match {
            font-weight: bold;
            font-size: 1.2rem;
            margin-top: 15px;
            display: none;
        }
    </style>
</head>
<body>
    <h1>Left-Right Bracket Tournament</h1>
    <div class="tree-container">
        <div id="left-tree" class="bracket"></div>
        <div id="final-container">
            <div id="final-match"></div>
        </div>
        <div id="right-tree" class="bracket"></div>
    </div>
    <div id="winner-display"></div>

    <script>
        const leftTreeRounds = [];
        const rightTreeRounds = [];
        let leftTreeWinner = null;
        let rightTreeWinner = null;

        async function fetchPlayers() {
            const response = await fetch('?fetch_players=1');
            return await response.json();
        }

        async function initializeTournament() {
    const players = await fetchPlayers();

    // Randomize the players array
    for (let i = players.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [players[i], players[j]] = [players[j], players[i]];
    }

    const halfLength = Math.floor(players.length / 2);
    let leftPlayers = [];
    let rightPlayers = [];

    // Handle even and odd player cases
    if (players.length % 2 === 0) {
        // If even number of players, split normally
        leftPlayers = players.slice(0, halfLength - 1); // All but the last two
        rightPlayers = players.slice(halfLength - 1);
    } else {
        // If odd number of players, leave one player in the left bracket
        leftPlayers = players.slice(0, halfLength + 1);
        rightPlayers = players.slice(halfLength + 1);
    }

    // Pair the last two players in the right bracket if even
    if (players.length % 2 === 0) {
        leftPlayers.push(rightPlayers.shift()); // Move one player to left to balance
    }

    initializeTree(leftPlayers, leftTreeRounds);
    initializeTree(rightPlayers, rightTreeRounds);

    renderBrackets();
}


        function initializeTree(players, treeRounds) {
            const firstRound = players.reduce((round, player, index, arr) => {
                if (index % 2 === 0) {
                    round.push({
                        player1: player,
                        player2: arr[index + 1] || null,
                        score1: 0,
                        score2: 0,
                        submitted: false
                    });
                }
                return round;
            }, []);
            treeRounds.push(firstRound);
        }

        function renderBrackets() {
            renderTree(leftTreeRounds, 'left-tree', 'Left');
            renderTree(rightTreeRounds, 'right-tree', 'Right');
            if (leftTreeWinner && rightTreeWinner) renderFinalMatch();
        }

        function renderTree(treeRounds, containerId, treeSide) {
            const container = document.getElementById(containerId);
            container.innerHTML = '';
            const renderedRounds = treeSide === 'Right' ? [...treeRounds].reverse() : treeRounds;

            renderedRounds.forEach((round, reverseIndex) => {
                const actualRoundIndex = treeSide === 'Right'
                    ? treeRounds.length - reverseIndex - 1
                    : reverseIndex;

                const roundDiv = document.createElement('div');
                roundDiv.classList.add('round');
                roundDiv.innerHTML = `<h3>${treeSide} Round ${actualRoundIndex + 1}</h3>`;
                container.appendChild(roundDiv);

                round.forEach((match, matchIndex) => {
                    const matchDiv = document.createElement('div');
                    matchDiv.classList.add('match');
                    matchDiv.innerHTML = `
                        <div class="player">
                            <span>${match.player1?.player_name || 'No Player'}</span>
                            <input type="number" value="${match.score1}" min="0"
                                onchange="updateScore('${treeSide}', ${actualRoundIndex}, ${matchIndex}, 1, this)">
                        </div>
                        ${match.player2 ? `
                        <div class="player">
                            <span>${match.player2.player_name}</span>
                            <input type="number" value="${match.score2}" min="0"
                                onchange="updateScore('${treeSide}', ${actualRoundIndex}, ${matchIndex}, 2, this)">
                        </div>` : ''}
                    `;
                    roundDiv.appendChild(matchDiv);
                });

                const submitButton = document.createElement('button');
                submitButton.textContent = 'Submit Scores';
                submitButton.onclick = () => submitRound(treeSide, actualRoundIndex);
                roundDiv.appendChild(submitButton);
            });
        }

        function updateScore(treeSide, roundIndex, matchIndex, player, input) {
            const tree = treeSide === 'Left' ? leftTreeRounds : rightTreeRounds;
            const match = tree[roundIndex][matchIndex];
            match[`score${player}`] = parseInt(input.value) || 0;
        }

        function submitRound(treeSide, roundIndex) {
            const tree = treeSide === 'Left' ? leftTreeRounds : rightTreeRounds;
            const currentRound = tree[roundIndex];
            if (currentRound.some(match => match.submitted)) {
                alert(`This ${treeSide} round has already been submitted!`);
                return;
            }

            for (const match of currentRound) {
                if (match.score1 === 0 && match.score2 === 0) {
                    alert("Fill in all scores before submitting!");
                    return;
                }
                if (match.score1 === match.score2) {
                    alert("There cannot be a tie. Please resolve the scores!");
                    return;
                }
            }

            currentRound.forEach(match => match.submitted = true);

            const winners = currentRound.map(match =>
                match.score1 > match.score2 ? match.player1 : match.player2
            );

            const nextRound = [];
            for (let i = 0; i < winners.length; i += 2) {
                const player1 = winners[i];
                const player2 = winners[i + 1] || null;
                nextRound.push({ player1, player2, score1: 0, score2: 0, submitted: false });
            }

            if (nextRound.length === 1 && nextRound[0].player2 === null) {
                if (treeSide === 'Left') {
                    leftTreeWinner = nextRound[0].player1;
                } else {
                    rightTreeWinner = nextRound[0].player1;
                }
                renderBrackets();
                return;
            }

            tree.push(nextRound);
            renderBrackets();
        }

        function renderFinalMatch() {
            const finalDiv = document.getElementById('final-match');
            finalDiv.style.display = 'block';
            finalDiv.innerHTML = `
                <h2>Final Match</h2>
                <div class="match">
                    <div class="player">
                        <span>${leftTreeWinner.player_name}</span>
                        <input type="number" id="final-left-score" value="0" min="0">
                    </div>
                    <div class="player">
                        <span>${rightTreeWinner.player_name}</span>
                        <input type="number" id="final-right-score" value="0" min="0">
                    </div>
                </div>
                <button onclick="submitFinal()">Submit Final</button>
            `;
        }


        function submitFinal() {
            const leftScore = parseInt(document.getElementById('final-left-score').value) || 0;
            const rightScore = parseInt(document.getElementById('final-right-score').value) || 0;
            const winnerDisplay = document.getElementById('winner-display');

            if (leftScore === rightScore) {
                alert("Final match cannot end in a tie!");
                return;
            }

            winnerDisplay.textContent = `Winner: ${
                leftScore > rightScore ? leftTreeWinner.player_name : rightTreeWinner.player_name
            }`;
        }

        initializeTournament();
    </script>
</body>
</html>
