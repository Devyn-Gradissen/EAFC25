<?php 

class DB_Handler { 

    // Variables
    public $username = "root";
    public $password = "";
    public $database = "eafc25_db";
    public $server = "localhost";
    

    // Constructor
    public function __construct() { 

    }

    // Class navigation
    public function handler($function, $data1 = NULL) {

        switch ($function) {
            case "connect":
                return $this->db_connect();
                
            case "jsonConvert":
                return $this->json_encode_data($data1);
                
            case "addUser":
                return $this->add_user($data1);

            case "playerForm":
                return $this->player_from();
         }

    }

    // == HTML Functions ==

    public function player_from() {

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

                <form method="POST">
                    <label for="name">Name:</label><br>
                        <input type="text" id="name" name="name"><br>
                    <label for="lname">Last Name:</label><br>
                        <input type="text" id="lname" name="lname"><br>
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
                    <!-- Send button -->
                    <input type="submit" value="Add Player" name="submit" id="submit">
                </form>

            </div>

        <?php

    }

    

    // Establish the Database Connection
    public function db_connect(){ 

        $connect = new mysqli($this->server, $this->username, $this->password, $this->database);

        if ($connect->connect_error) { 
            die("ERROR:". $connect->connect_error);
        }

        return $connect;

    }

    // Encoding the data to a JSON format
    public function json_encode_data($userdata) {

        if ($userdata != NULL) {
            $converted_data = json_encode($userdata, JSON_PRETTY_PRINT);

            return $converted_data;

        } else {
            echo "ERROR: The data to convert = NULL";
        }

    }

    public function add_user($new_user) { 

        var_dump($new_user);

        // Setting up PDO
        $dsn = "mysql:host=$this->server;dbname=$this->database;charset=utf8mb4";
        $pdo = new PDO($dsn, $this->username, $this->password);
        
        // Decoding JSON_Array
        if ($new_user != NULL) {
            $new_user = json_decode($new_user, true);

            $stmt = $pdo->prepare("INSERT INTO players (player_name, player_lname, player_div) VALUES (:player_name, :player_lname, :player_div)");

            $stmt->execute([
                ':player_name' => $new_user['name'],
                ':player_lname'=> $new_user['lname'],
                ':player_div'=> $new_user['div']  
            ]);

            echo "player added";

        } else {
            echo "ERROR: new user array = NULL";
        }
    }

    public function req_users() {
        
    }

}

