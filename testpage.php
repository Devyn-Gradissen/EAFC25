<?php 
include './DB_Functions/assets/database_handler.php'; 
$db_handler = new DB_Handler;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <?php $db_handler->handler("playerForm"); ?>

</body>
</html>