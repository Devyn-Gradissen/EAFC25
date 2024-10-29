    
    <?php 
    
        // Including the Class
        include("./assets/database_handler.php");

        // Establishing the Class
        $db_handler = new DB_Handler(); 

        // DB_Handler Functions
        //
        // Parameters:
        // db_connect() = "connect"
        // json_encode_data() = "jsonConvert" (extra param: "dataToConvert")
        // add_user() = "addUser" (extra param: "userToAdd")
        // player_form() = "playerForm"

    ?>
    
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>TEST</title>
            <link rel="stylesheet" href="./assets/css/bootstrap/bootstrap.css">
            <link rel="stylesheet" href="./assets/css/style.css">

            <!-- Font -->
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
        </head>

        <body>
            
            <!-- Database Connection Function -->
            <?php $db_handler->handler("connect") ?>

            <?php $db_handler->handler("playerForm") ?>

            <?php

                //var_dump($_POST);

                if (isset($_POST['submit'])) {
                    if (isset($_POST['name']) && isset($_POST['lname']) && isset($_POST['div'])) {

                        $conv_userdata = $db_handler->handler("jsonConvert", $_POST);

                        $db_handler->handler("addUser", $conv_userdata);
                    } else {
                        echo "Something is wrong with the POST";
                    }
                }

            ?>
        </body>
    </html>