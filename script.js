// script.js

let players = []; // Array to store players
let currentRound = []; // Array for players in the current round
let roundNum = 1; // Round counter
let roundHistory = []; // Array to store history of each round

// Adds a player to the tournament
function addPlayer() {
    const playerNameInput = document.getElementById("playerName");
    const playerName = playerNameInput.value.trim();

    if (playerName === "") {
        alert("Voer een geldige naam in");
        return;
    }

    players.push({ name: playerName });
    playerNameInput.value = ""; // Clear the input field

    updateBracket(); // Update the bracket to include the new player
}

// Removes a specified player from the tournament
function removePlayer() {
    const playerNameToRemove = prompt("Vul de naam in van de speler die je wilt verwijderen");

    if (!playerNameToRemove) {
        return;
    }

    const index = players.findIndex(player => player.name === playerNameToRemove.trim());

    if (index === -1) {
        alert("Speler niet gevonden!");
    } else {
        players.splice(index, 1); // Remove the player from the array
        updateBracket(); // Update the bracket to reflect removal
    }
}

// Edits a specified player's name in the tournament
function editPlayer() {
    const playerNameToEdit = prompt("Pas de spelers naam aan");

    if (!playerNameToEdit) {
        return;
    }

    const index = players.findIndex(player => player.name === playerNameToEdit.trim());

    if (index === -1) {
        alert("Speler niet gevonden!");
    } else {
        const newName = prompt("Geef de naam van de nieuwe speler:", players[index].name);
        if (newName) {
            players[index].name = newName.trim(); // Update name
            updateBracket();
        }
    }
}

// Shuffles players randomly to create varied matchups
function shufflePlayers() {
    for (let i = players.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [players[i], players[j]] = [players[j], players[i]]; // Swap players
    }
}

// Updates the tournament bracket display
function updateBracket() {
    const bracket = document.getElementById("bracket");
    bracket.innerHTML = ""; // Clear the bracket

    // Display results of previous rounds
    roundHistory.forEach((round, index) => {
        const previousRoundDiv = document.createElement("div");
        previousRoundDiv.classList.add("round");
        previousRoundDiv.innerHTML = `<h3>Ronde ${index + 1}</h3>`;

        round.forEach(match => {
            const matchDiv = document.createElement("div");
            matchDiv.classList.add("match");
            matchDiv.innerHTML = `
                <div>${match.player1.name} ${match.score1} - ${match.score2} ${match.player2.name}</div>
            `;
            previousRoundDiv.appendChild(matchDiv);
        });

        bracket.appendChild(previousRoundDiv);
    });

    // Shuffle players only in the first round
    if (roundNum === 1) {
        shufflePlayers();
    }

    // Render the current round of matches
    if (players.length > 1) {
        currentRound = [...players];
        renderBracket(currentRound); // Display the first round matches
    }
}

// Renders a single round in the bracket
function renderBracket(roundPlayers) {
    const bracket = document.getElementById("bracket");

    const roundDiv = document.createElement("div");
    roundDiv.classList.add("round");
    roundDiv.innerHTML = `<h3>Ronde ${roundNum} </h3>`;

    for (let i = 0; i < roundPlayers.length; i += 2) {
        if (i + 1 < roundPlayers.length) {
            const matchDiv = document.createElement("div");
            matchDiv.classList.add("match");
            matchDiv.innerHTML = ` 
                <div>${roundPlayers[i].name} vs ${roundPlayers[i + 1].name}</div>
                <input type="number" id="score-${i}" placeholder="Score" value="0" min="0">
                <input type="number" id="score-${i + 1}" placeholder="Score" value="0" min="0">
            `;
            roundDiv.appendChild(matchDiv);
        } else {
            const matchDiv = document.createElement("div");
            matchDiv.classList.add("match");
            matchDiv.innerHTML = `<div>${roundPlayers[i].name} gaat door naar de volgende ronde!</div>`;
            roundDiv.appendChild(matchDiv);
        }
    }

    bracket.appendChild(roundDiv);
}

// Advances to the next round by evaluating scores
function nextRound() {
    const nextRound = [];
    const roundResults = []; // Store results of the current round

    for (let i = 0; i < currentRound.length; i += 2) {
        if (i + 1 < currentRound.length) {
            const score1 = parseInt(document.getElementById(`score-${i}`).value, 10);
            const score2 = parseInt(document.getElementById(`score-${i + 1}`).value, 10);

            if (score1 > score2) {
                nextRound.push(currentRound[i]); // Player 1 advances
            } else if (score2 > score1) {
                nextRound.push(currentRound[i + 1]); // Player 2 advances
            } else {
                alert("Het kan niet gelijk staan! Vul een geldige score in."); // Prevent ties
                return;
            }

            // Record the match result
            roundResults.push({
                player1: currentRound[i],
                score1: score1,
                player2: currentRound[i + 1],
                score2: score2
            });
        } else {
            nextRound.push(currentRound[i]); // Handle odd player moving up
        }
    }

    roundHistory.push(roundResults); // Save results for display
    currentRound = nextRound;
    players = currentRound;
    roundNum++;

    if (currentRound.length > 1) {
        updateBracket();
    } else {
        displayWinner(currentRound[0].name); // Display the tournament winner
    }
}

// Displays the winner at the end of the tournament
function displayWinner(winnerName) {
    const winnerDisplay = document.getElementById("winnerDisplay");
    winnerDisplay.innerHTML = `De winnaar van het toernooi is: ${winnerName}!`;
}

// Resets the tournament back to the initial state
function resetTournament() {
    players = [];
    currentRound = [];
    roundNum = 1;
    roundHistory = [];
    document.getElementById("playerName").value = "";
    document.getElementById("bracket").innerHTML = ""; // Clear bracket display
    document.getElementById("winnerDisplay").innerHTML = ""; // Clear winner display
}
