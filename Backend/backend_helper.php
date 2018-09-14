<?php
/**
 * Easier to connect to DB without create it again and agin.
 * User: yyuueexxiinngg
 * Date: 2018/9/11
 * Time: 2:11
 */
require dirname(__FILE__) . '/config.php';
require __DIR__ . '/vendor/autoload.php';
class mysql_helper
{
    public $conn;

    public function __construct()
    {
        // Checking whether the host address contains ':' which is for determine port number of DB
        $port_idx = strpos(DB_HOST, ':');

        if ($port_idx === false) {
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        } else {
            $conn = new mysqli(substr(DB_HOST, 0, $port_idx), DB_USER, DB_PASSWORD, DB_NAME, substr(DB_HOST, $port_idx + 1));
        }

        if ($conn->connect_error) {
            throw new Exception($conn->connect_error);
        } else {
            $this->conn = $conn;
        }
    }

    public function query($query)
    {
        $result = $this->conn->query($query);

        if ($result->num_rows > 0) {
            return $result;
        } else {
//            throw new Exception("DB connected, yet no return value, something wrong with query?");
            return false;
        }
    }

    public function close(MySQLi $conn = null)
    {
        if($conn){
            $conn->close();
        } else{
            $this->conn->close();
        }
    }
}

// Adding header for allowing access from different origin
header('Access-Control-Allow-Origin: *');

// Adding header for allowing POST and more
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Connection, User-Agent, Cookie, Authorization');

// Adding header for session
header('Access-Control-Allow-Credentials: true');

// Adding header for returning json
header('Content-type: application/json');

// Axios would perform post twice, the second one is the real request. First request with option header for testing the headers.
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit();
}
