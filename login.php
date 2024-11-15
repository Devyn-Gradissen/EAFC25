<?php 

include("./DB_Functions/assets/database_handler.php");

$handler = new DB_Handler;
$handler->handler("connect");

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./DB_Functions/assets/css/style.css">
    <title>Login</title>
</head>

<body class="loginBody">
    
    <?php $handler->handler("navbar"); ?>


    <div class="login">

        <h3>Login</h3><br>

        <form method="POST">
            
            <label for="name">Name:</label><br><br>
                <input type="text" id="name" name="name">
            
            <br><br>

            <label for="password">Password:</label><br><br>
                <input type="password" id="password" name="password">

            <br><br>

            <input type="submit" value="Register" name="submit" id="submit">

            <?php 
                if (isset($_POST["submit"])) {
                    $handler->handler("login", $_POST);
                }
            ?>

        </form>

    </div>

</body>

</html>
