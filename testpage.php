<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Players</title>
</head>
<body>

    <h1>Add New Player</h1>

    <?php 
        // Include the PHP class file
        include 'DB_Handler.php';

        // Instantiate the DB_Handler class
        $db_handler = new DB_Handler();

        // Display the player form
        $db_handler->handler("playerForm");

        // Process form data when submitted
        if (isset($_POST['submit'])) {
            // Prepare data to send to addUser function
            $new_user = [
                "name" => $_POST['name'],
                "lname" => $_POST['lname'],
                "div" => $_POST['div']
            ];

            // Convert data to JSON format
            $new_user_json = json_encode($new_user);

            // Add the player using the addUser function
            $db_handler->handler("addUser", $new_user_json);

            echo "<p>Player added successfully!</p>";
        }
    ?>

</body>
</html>
