<?php 

class DB_Handler { 

    // Public Variables
    public $username = "root";
    public $password = "";
    public $database = "eafc25_db";
    public $server = "localhost";
    

    // Constructor
    public function __construct() { 

    }

    /* 
    
    Deze FUNCTION werkt als een navigatie naar de verschillende FUNCTIES die deze CLASS heeft.

    De FUNCTION verwacht 2 parameters:
        - $function STRING "function die je wil roepen"
        - $data1 VAR "afhankelijk van de functie" OPTIONAL

    In de main code kan je de verschillen FUNCTIONS aanroepen op deze manier

        $db_handler = new DB_Handler();
        $db_handler->handler("function", "data");

    De functions die je kan roepen zijn;


    */
    public function handler($function, $data1 = NULL) {

        switch ($function) {
            case "connect":
                return $this->db_connect();
                
            case "jsonEncode":
                return $this->json_encode_data($data1);
                
            case "addPlayer":
                return $this->add_player($data1);

            case "requestPlayers" :
                return $this->req_players();

            case "playerForm":
                return $this->player_form();
            
            case "navbar":
                return $this->navbar();

            case "count":
                return $this->player_counter();

            case "addAdmin":
                return $this->add_admin($data1);

            case "login":
                return $this->login_admin($data1);
         }

    }


    // Complete form to add players working with the addUser function
    public function player_form() {

        ?>

            <style>
                .playerForm {
                    width: 50%;
                    margin: 1rem;
                    padding: 1rem;
                    color: var(--fifaWhiteSmoke);
                }

                .playerForm label {
                    font-size: 26px;
                }

                .playerForm input {
                    font-family: Verdana;
                    font-size: 26px;
                    padding: 0.5rem;
                    width: 100%;
                    color: var(--fifaWhiteSmoke);
                    background-color: var(--fifaGrey);
                    border: solid 1px white;
                    border-radius: 0.5rem;
                }

                .playerForm select {
                    font-family: Verdana;
                    font-size: 26px;
                    color: var(--fifaWhiteSmoke);
                    background-color: var(--fifaGrey);
                    border: solid 1px white;
                    padding: 0.5rem;
                    border-radius: 0.5rem;
                }

                #submit {
                    background-color: var(--fifaMint);
                    margin-top: 1rem;
                    padding: 1rem;
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
                    cursor: pointer;
                }
            </style>

            <div class="playerForm">

                <!-- form using $_POST --> 
                <form method="POST">
                    <!-- player name automatically gets trimmed to just the Initial --> 
                    <label for="name">Name:</label><br><br>
                        <input type="text" id="name" name="name"><br><br>
                    <!-- player lastname --> 
                    <label for="lname">Last Name:</label><br><br>
                        <input type="text" id="lname" name="lname"><br><br>
                    <!-- player division --> 
                    <label for="div">Division:</label><br><br>
                        <select id="div" name="div">
                            <option value="10">10</option>
                            <option value="9">9</option>
                            <option value="8">8</option>
                            <option value="7">7</option>
                            <option value="6">6</option>
                            <option value="5">5</option>
                            <option value="4">4</option>
                            <option value="3">3</option>
                            <option value="2">2</option>
                            <option value="1">1</option>
                        </select> <br>
                    <!-- send button -->
                    <input type="submit" value="Add Player" name="submit" id="submit">
                </form>

            </div>

        <?php

    }


    public function navbar(){

        ?> 
        
            <style>
                nav {
                    margin: 0;
                    padding: 0;
                    width: 100vw;
                    font-family: Verdana;
                }
                
                nav ul {
                    list-style-type: none;
                    margin: 0;
                    padding: 0;
                    display: flex;
                    align-items: center; /* Zorgt ervoor dat de items verticaal gecentreerd zijn */
                    background-color: var(--fifaGrey);
                    border-bottom: 1px solid white;
                }
                
                nav ul li {
                    display: inline-flex;
                    align-items: center;
                }
                
                .navbarLogo {
                    width: auto; /* Past zich aan de inhoud aan */
                    padding: 0;
                }
                
                .navbarLogo img {
                    max-height: 3rem; /* Past de afbeelding aan de hoogte van de navigatie aan */
                    width: auto; /* Behoudt de verhoudingen */
                    object-fit: contain; /* Zorgt ervoor dat de afbeelding binnen de container past */
                }
                
                nav ul li a {
                    display: block;
                    color: white;
                    font-weight: bold;
                    text-align: center;
                    padding: 1rem;
                    text-decoration: none;
                    background-color: var(--fifaGrey);
                    transition: background-color 1s ease-in-out;
                }
                
                nav ul li a:hover {
                    background-color: #07F468;
                    font-weight: bold;
                }
            </style>

            <!-- navbar -->
            <nav>
                <ul>
                    <li class="navbarLogo"><img src="./DB_Functions/assets/img/ea-removebg.png"></li>
                    <li><a href="./index.php">Tournament</a></li>
                    <li><a href="./add_player.php">Add Player</a></li>
                    <li><a href="./login.php">Admin</a></li>
                </ul>
            </nav>
        
        <?php

    }
    

    // Establish the Database Connection
    public function db_connect(){ 

        // making the connection with the public variables
        $connect = new mysqli($this->server, $this->username, $this->password, $this->database);

        if ($connect->connect_error) { 
            die("ERROR:". $connect->connect_error);
        }

        return $connect;

    }

    // Encoding the data to a JSON format
    public function json_encode_data($userdata) {

        if ($userdata != NULL) {
            // encoding $userdata
            $converted_data = json_encode($userdata, JSON_PRETTY_PRINT);

            return $converted_data;

        } else {
            // echo "ERROR: The data to convert = NULL";
        }

    }

    public function add_admin($new_admin) {
        
        try{
            // Setting up PDO
            $dsn = "mysql:host=$this->server;dbname=$this->database;charset=utf8mb4";
            $pdo = new PDO($dsn, $this->username, $this->password);

            // setting up the query
            $stmt = $pdo->prepare("INSERT INTO users (user_name, user_password) VALUES (:user_name, :user_password)");

            // Bind parameters
            $stmt->bindParam(':user_name', $new_admin['name']);
            
            // Hash the password for security before inserting
            $hashedPassword = password_hash($new_admin['password'], PASSWORD_BCRYPT);
            $stmt->bindParam(':user_password', $hashedPassword);

            // Execute the query
            $stmt->execute();

            echo "User successfully registered!";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }



    }

    public function login_admin($login) {
        try {
            // Check if username and password are set in $_POST
            if (!isset($login['name']) || !isset($login['password'])) {
                echo "Username or password not provided!";
                return;
            }
    
            // Setting up PDO
            $dsn = "mysql:host=$this->server;dbname=$this->database;charset=utf8mb4";
            $pdo = new PDO($dsn, $this->username, $this->password);
    
            // Prepare and execute the SQL query to fetch the user by username
            $stmt = $pdo->prepare("SELECT user_password FROM users WHERE user_name = :user_name");
            $stmt->bindParam(':user_name', $login['name']);
            $stmt->execute();
    
            // Fetch the user record
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($user) {
                // Verify the password
                if (password_verify($login['password'], $user['user_password'])) {
                    echo "Login successful!";
                    // Optionally, set session variables or perform other login actions here
                } else {
                    echo "Invalid username or password.";
                }
            } else {
                echo "User not found.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Adding users to the database
    public function add_player($new_player) { 

        //var_dump($new_user);

        // Setting up PDO
        $dsn = "mysql:host=$this->server;dbname=$this->database;charset=utf8mb4";
        $pdo = new PDO($dsn, $this->username, $this->password);
        
        // Decoding JSON_Array
        if ($new_player != NULL) {
            $new_player = json_decode($new_player, true);

            // trimming to just the Initial
            $only_initial = strtoupper(substr($new_player["lname"],0,1));

            // setting up the query
            $stmt = $pdo->prepare("INSERT INTO players (player_name, player_lname, player_div) VALUES (:player_name, :player_lname, :player_div)");

            // executing the query
            $stmt->execute([
                ':player_name' => $new_player['name'],
                ':player_lname'=> $only_initial,
                ':player_div'=> $new_player['div']  
            ]);

            //echo "player added";

        } else {
            echo "ERROR: new user array = NULL";
        }
    }

    public function del_player($return_URL) {

            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);

            if (!isset($_GET['player_id']) || !is_numeric($_GET['player_id'])) {
                echo "Invalid request.";
                return;
            }
    
            $player_id = intval($_GET['player_id']); // Sanitize the input
     
            // Setting up PDO
            $dsn = "mysql:host=$this->server;dbname=$this->database;charset=utf8mb4";
            $pdo = new PDO($dsn, $this->username, $this->password); 

            try {
                
                $query = "DELETE FROM players WHERE player_id = :player_id";
                $execute = $pdo->prepare($query);
        
                // Bind the parameter
                $execute->bindParam(':player_id', $player_id, PDO::PARAM_INT);
                $execute->execute();


                header("Location: $return_URL");
                exit();
        
            } catch (PDOException $e) {
                // Handle any errors
                echo "Error: " . $e->getMessage();
            }

    }

    // requesting all users from the database
    public function req_players() {
       
        try {

            // Setting up PDO
            $dsn = "mysql:host=$this->server;dbname=$this->database;charset=utf8mb4";
            $pdo = new PDO($dsn, $this->username, $this->password);

            // setting up the query
            $stmt = $pdo->prepare("SELECT * FROM players");
            // executing the array
            $stmt->execute();

            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // encoding the data as JSON arrays
            $userArray = json_encode($users);

            return $userArray;

        } catch (PDOException $e) {
            return json_encode(['error' => $e-> getMessage()]);
        }

    }

    // counting the users from the database
    public function player_counter() {

        // Setting up PDO
        $dsn = "mysql:host=$this->server;dbname=$this->database;charset=utf8mb4";
        $pdo = new PDO($dsn, $this->username, $this->password);

            // Prepare and execute the query
            $stmt = $pdo->query("SELECT COUNT(*) FROM players");

            // Fetch the count as an integer
            $count = (int) $stmt->fetchColumn();

            return $count;

    }

}

