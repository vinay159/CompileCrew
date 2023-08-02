<?php
session_start();
class UserManagement
{
    private $db;
    private $errors = array();

    // ~~~~~~~~~~~~~  function to establish database connections ~~~~~~~~~~~~~ 
    public function __construct($host, $username, $password, $database_name)
    {
        // ~~~~~~~~~~~~~  Establish database connection ~~~~~~~~~~~~~ 
        $this->db = mysqli_connect($host, $username, $password, $database_name);

        // ~~~~~~~~~~~~~  Check if the database connection is successful ~~~~~~~~~~~~~ 
        if (!$this->db) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // ~~~~~~~~~~~~~  Create the database and table if they don't exist ~~~~~~~~~~~~~ 
        $this->createDatabaseAndTable();
    }

    // ~~~~~~~~~~~~~ functions to create a database and tables respectively ~~~~~~~~~~~~~ 
    private function createDatabaseAndTable()
    {
        $database_name = "mydb";
        $table_name = "users";
        $add_cart_table_name = "add_cart";
        $create_db_query = "CREATE DATABASE IF NOT EXISTS $database_name";
        $create_table_query = "CREATE TABLE IF NOT EXISTS $database_name.$table_name (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL
        )";

        // Create the database if it doesn't exist
        mysqli_query($this->db, $create_db_query);

        // Create the users table if it doesn't exist
        mysqli_query($this->db, $create_table_query);
        
    }

    // ~~~~~~~~~~~~~  functions for register users ~~~~~~~~~~~~~ 
    public function registerUser($username, $email, $password_1, $password_2)
    {
        $username = mysqli_real_escape_string($this->db, $username);
        $email = mysqli_real_escape_string($this->db, $email);
        $password_1 = mysqli_real_escape_string($this->db, $password_1);
        $password_2 = mysqli_real_escape_string($this->db, $password_2);

        // ~~~~~~~~~~~~~  Form validation: Check for required fields and password match ~~~~~~~~~~~~~ 
        if (empty($username)) {
            $this->errors[] = "Username is required";
        }
        if (empty($email)) {
            $this->errors[] = "Email is required";
        }
        if (empty($password_1)) {
            $this->errors[] = "Password is required";
        }
        if ($password_1 != $password_2) {
            $this->errors[] = "The two passwords do not match";
        }

        // ~~~~~~~~~~~~~ Check to see whether a user with the same username and/or email already exists. ~~~~~~~~~~~~~ 
        $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
        $result = mysqli_query($this->db, $user_check_query);
        $user = mysqli_fetch_assoc($result);

        // ~~~~~~~~~~~~~ If user exists ~~~~~~~~~~~~~ 
        if ($user) { 
            if ($user['username'] === $username) {
                $this->errors[] = "Username already exists";
            }

            if ($user['email'] === $email) {
                $this->errors[] = "Email already exists";
            }
        }

        // ~~~~~~~~~~~~~  If no errors, register the user ~~~~~~~~~~~~~ 
        if (empty($this->errors)) {
            // ~~~~~~~~~~~~~ Before saving the password in the database, encrypt it. ~~~~~~~~~~~~~ 
            $password = md5($password_1); 

            $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
            mysqli_query($this->db, $query);
            $_SESSION['username'] = $username;
            $_SESSION['success'] = "You are now logged in";
            header('location: index.php');
        }
    }

    public function loginUser($username, $password)
    {
        $username = mysqli_real_escape_string($this->db, $username);
        $password = mysqli_real_escape_string($this->db, $password);

        // ~~~~~~~~~~~~~ Form validation: Look for mandatory fields. ~~~~~~~~~~~~~ 
        if (empty($username)) {
            $this->errors[] = "Username is required";
        }
        if (empty($password)) {
            $this->errors[] = "Password is required";
        }

        // ~~~~~~~~~~~~~ If there are no problems/errors, try to log the person in. ~~~~~~~~~~~~~ 
        if (empty($this->errors)) {
            $password = md5($password);
            $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
            $results = mysqli_query($this->db, $query);
            if (mysqli_num_rows($results) == 1) {
              $_SESSION['username'] = $username;
              $_SESSION['success'] = "You are now logged in";
              header('location: index.php');
            } else {
                $this->errors[] = "Wrong username/password combination";
            }
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }
}

// ~~~~~~~~~~~~~ Database credentials and stuffs ~~~~~~~~~~~~~ 
$host = 'localhost';
$username = 'root';
$password = '';
$database_name = 'mydb';

$userManagement = new UserManagement($host, $username, $password, $database_name);

// ~~~~~~~~~~~~~  Handle registration ~~~~~~~~~~~~~ 
if (isset($_POST['reg_user'])) {
    $userManagement->registerUser($_POST['username'], $_POST['email'], $_POST['password_1'], $_POST['password_2']);
}

// ~~~~~~~~~~~~~ Handle login ~~~~~~~~~~~~~ 
if (isset($_POST['login_user'])) {
    $userManagement->loginUser($_POST['username'], $_POST['password']);
}

// ~~~~~~~~~~~~~ To obtain errors (if any) following registration/login attempts ~~~~~~~~~~~~~ 
$errors = $userManagement->getErrors();


?>