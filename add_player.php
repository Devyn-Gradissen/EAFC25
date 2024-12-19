<?php 
    include('./DB_Functions/assets/database_handler.php');
    $db_handler = new DB_Handler;

    
    // echo "Current Directory: " . __DIR__;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Players</title>
        <link rel="stylesheet" href="./assets/css/style.css">
    </head>

    <style>
        :root {
            --fifaMint: #07F468;
            --fifaGrey: #161616;
            --fifaWhiteSmoke: #f3f3f3;
        }

        *{
            padding: 0;
            margin: 0;
            color: whitesmoke;
        }

        .loginBody {
            background-color: black;
            background-image: url(./DB_Functions/assets/img/FC25_Base_BG.png);
            background-size: cover;
            font-family: Verdana;
        }

        .login {
            background-color: var(--fifaGrey);
            display: block;
            width: 33%;
            margin: auto;
            margin-top: 10%;
            padding: 1rem;
            color: var(--fifaWhiteSmoke);
            border-radius: 0.5rem;
            border: #f3f3f3 2px solid;
        }

        .login label {
            font-size: 20px;
        }

        .login input {
            font-family: Verdana;
            font-size: 20px;
            padding: 0.5rem;
            width: 97%;
            color: var(--fifaWhiteSmoke);
            background-color: var(--fifaGrey);
            border: solid 1px white;
            border-radius: 0.5rem;
        }

        .login h3 {
            margin: 0;
            padding: 0;
        }

        #submit {
            background-color: var(--fifaMint);
            margin-top: 1rem;
            width: 50%;
            border-radius: 2rem;
            border: solid 1px var(--fifaMint);
        }

        
        #submit:hover {
            background-color: var(--fifaMint);
            margin-top: 1rem;
            width: 50%;
            border-radius: 2rem;
            border: solid 1px var(--fifaMint);
            font-weight: bold;
        }

        .adminPage {
            display: flex;
            width: 100%;
            height: 90vh;
            justify-content: flex-start;
            align-items: flex-start;
        }

        .playerAdd {
            display: flex;
            width: 50%;
        }

        .playerTable {
            display: flex;
            width: 50%;
        }

        .playerTable table {
            color: whitesmoke;
            font-size: 20px;
            width:100%;
            border: 0.25rem solid whitesmoke;
            margin: 1rem; 
        }

        .playerTable table tr th {
            font-size: 20px;
            padding: 0.5rem;
            text-align: left;
            border-bottom: 0.25rem solid whitesmoke;
        }

        .playerTable table tr td {
            font-size: 20px;
            padding: 0.5rem;
        }
    </style>

    <body class="loginBody">
        
        <?php 
            $db_handler->handler("navbar");
        ?> 

        <div class="adminPage">

            <div class="playerAdd">
                <?php
                    $db_handler->handler("playerForm");

                    $convertedPlayer = $db_handler->handler("jsonEncode", $_POST);
                    if ($convertedPlayer != NULL) {
                        $db_handler->handler("addPlayer", $convertedPlayer);
                    }

                ?>
            </div>

            <div class="playerTable">

                <table>

                    <tr>
                        <th>ID</th>
                        <th>Voorletters</th>
                        <th>Initiaal</th>
                        <th>Division</th>
                        <th></th>
                    </tr>

                    <?php 
                    
                        $usersJson = $db_handler->handler("requestPlayers");
                        $users = json_decode($usersJson, true);

                        // var_dump($users);
                        
                        if (is_array($users)) {
                            foreach ($users as $user) {
                                ?>

                                <tr>
                                    <td><?php echo $user["player_id"] ?></td>
                                    <td><?php echo $user["player_name"] ?></td>
                                    <td><?php echo $user["player_lname"] ?></td>
                                    <td><?php echo $user["player_div"] ?></td>
                                    <td>
                                        <a href="del_player.php?player_id=<?php echo $user['player_id']; ?>" style="color: #07F468">Delete</a>
                                    </td>
                                </tr>

                                <?php
                            }
                        }

                    ?>

                </table>


            </div>

        </div>

    </body>
</html>