<?php

    require_once "./DB_Functions/assets/database_handler.php";

    $db_handler = new DB_Handler();


    //Put the correct filepath in here! 
    $db_handler->del_player("http://localhost/GitHub/EAFC25/add_player.php");

?>