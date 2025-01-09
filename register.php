<?php 

include('./DB_Functions/assets/database_handler.php');

$handler = new DB_Handler;
$handler->handler("connect");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./DB_Functions/assets/css/style.css">
    <title>Register</title>
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
</style>

<body class="loginBody">
    
    <?php $handler->handler("navbar"); ?>

    <div class="login">

        <h3>Register</h3><br>

        <form method="POST">
            
            <label for="name">Name:</label><br><br>
                <input type="text" id="name" name="name" required>
            
            <br><br>

            <label for="password">Password:</label><br><br>
                <input type="password" id="password" name="password" required>

            <br><br>

            <input type="submit" value="Register" name="submit" id="submit">

            <?php 
                if (isset($_POST["submit"])) {
                    $handler->handler("addAdmin", $_POST); 
                }
            ?>

        </form>

    </div>

</body>

</html>
