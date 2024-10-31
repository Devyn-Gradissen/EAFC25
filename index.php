<?php 
include './DB_Functions/assets/database_handler.php';
$db_handler = new DB_Handler;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tournament Bracket</title>
    <!-- Link to external CSS file -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>FIFA Toernooi</h1>

        <div>
            <input type="text" id="playerName" placeholder="Typ een naam in">
            <button onclick="addPlayer()">Voeg speler toe</button>
            <button onclick="editPlayer()">Aanpassen</button>
            <button onclick="removePlayer()">Verwijderen</button>
            <button onclick="resetTournament()">Reset Toernooi</button> <!-- Reset button -->
        </div>

        <div id="bracket" class="bracket"></div>
        <button onclick="nextRound()">Verstuur</button>

        <div class="winner" id="winnerDisplay"></div> <!-- Winner display section -->
    </div>

    <!-- Link to external JavaScript file -->
    <script src="script.js"></script>
</body>
</html>
