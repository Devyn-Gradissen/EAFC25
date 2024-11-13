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
        - connect: "connect met de database"
        - jsonConvert: "convert de $_POST array naar een JSON Array voor makkelijk gebruik in JAVASCRIPT"
        - addUser: "deze function voegt spelers toe aan de database"
        - requestUsers: "deze function roept alle spelers op en RETURNS ze als JSON arrays"
        - playerForm: "dit is een volledig gestylde HTML form die samen werkt met de addUser FUNCTION"
        - count: "deze function RETURNS een INT van de hoeveelheid spelers in de database"

    */
    public function handler($function, $data1 = NULL) {

        switch ($function) {
            case "connect":
                return $this->db_connect();
                
            case "jsonConvert":
                return $this->json_encode_data($data1);
                
            case "addUser":
                return $this->add_user($data1);

            case "requestUsers" :
                return $this->req_users();

            case "playerForm":
                return $this->player_form();

            case "count":
                return $this->player_counter();

         }

    }


    // Complete form to add players working with the addUser function
    public function player_form() {

        ?>

            <style>
                .playerForm {
                    width: 25%;
                    margin: 1rem;
                    padding: 1rem;
                    color: var(--fifaWhiteSmoke);
                }

                .playerForm label {
                    font-size: 20px;
                }

                .playerForm input {
                    font-family: Verdana;
                    font-size: 20px;
                    padding: 0.5rem;
                    width: 100%;
                    color: var(--fifaWhiteSmoke);
                    background-color: var(--fifaGrey);
                    border: solid 1px white;
                    border-radius: 0.5rem;
                }

                .playerForm select {
                    font-family: Verdana;
                    font-size: 20px;
                    color: var(--fifaWhiteSmoke);
                    background-color: var(--fifaGrey);
                    border: solid 1px white;
                    padding: 0.5rem;
                    border-radius: 0.5rem;
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

            <div class="playerForm">

                <!-- form using $_POST --> 
                <form method="POST">
                    <!-- player name automatically gets trimmed to just the Initial --> 
                    <label for="name">Name:</label><br>
                        <input type="text" id="name" name="name"><br>
                    <!-- player lastname --> 
                    <label for="lname">Last Name:</label><br>
                        <input type="text" id="lname" name="lname"><br>
                    <!-- player division --> 
                    <label for="div">Division:</label><br>
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
            echo "ERROR: The data to convert = NULL";
        }

    }

    public function add_admin() {
        
    }

    // Adding users to the database
    public function add_user($new_user) { 

        //var_dump($new_user);

        // Setting up PDO
        $dsn = "mysql:host=$this->server;dbname=$this->database;charset=utf8mb4";
        $pdo = new PDO($dsn, $this->username, $this->password);
        
        // Decoding JSON_Array
        if ($new_user != NULL) {
            $new_user = json_decode($new_user, true);

            // trimming to just the Initial
            $only_initial = strtoupper(substr($new_user["lname"],0,1));

            // setting up the query
            $stmt = $pdo->prepare("INSERT INTO players (player_name, player_lname, player_div) VALUES (:player_name, :player_lname, :player_div)");

            // executing the query
            $stmt->execute([
                ':player_name' => $new_user['name'],
                ':player_lname'=> $only_initial,
                ':player_div'=> $new_user['div']  
            ]);

            //echo "player added";

        } else {
            echo "ERROR: new user array = NULL";
        }
    }

    // requesting all users from the database
    public function req_users() {
       
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

