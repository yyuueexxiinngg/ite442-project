<?php
/**
 * Easier to connect to DB without create it again and agin.
 * User: yyuueexxiinngg
 * Date: 2018/9/11
 * Time: 2:11
 */
require dirname(__FILE__) . '/config.php';
require __DIR__ . '/vendor/autoload.php';

use \Firebase\JWT\JWT;

define('SECRET_KEY', 'ite442_project');
define('ALGORITHM', 'HS256');
$dev = false;

//error_reporting(0);
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

        if ($result) {
            if(isset($result->num_rows)){
                if ($result->num_rows > 0) {
                    return $result;
                }
            }
            return true;
        }
        return false;
    }

    public function close(MySQLi $conn = null)
    {
        if ($conn) {
            $conn->close();
        } else {
            $this->conn->close();
        }
    }

    public function getAllRow($table, $columns = array('*'))
    {
        $select = $columns[0];
        foreach ($columns as $idx => $col) {
            if ($idx == 0) {
                continue;
            }
            $select = $select . ",$col";
        }

        $query = "SELECT $select FROM `$table`";
        $data = $this->query($query);

        if ($data) {
            $return = array();
            while ($row = $data->fetch_assoc()) {
                $return[] = $row;
            }
            return $return;
        } else {
            badRequest();
            return array("status" => "error", "errorType" => "400", "msg" => "No $table returned");
        }
    }

    public function getAllRowWithQuery($query)
    {
        $data = $this->query($query);

        if ($data) {
            $return = array();
            while ($row = $data->fetch_assoc()) {
                $return[] = $row;
            }
            return $return;
        } else {
            badRequest();
            return array("status" => "error", "errorType" => "400", "msg" => "No data returned");
        }
    }

    public function getRowsLike($table, $where, $like, $columns = array('*'))
    {
        $select = $columns[0];
        foreach ($columns as $idx => $col) {
            if ($idx == 0) {
                continue;
            }
            $select = $select . ",$col";
        }

        $query = "SELECT $select FROM `$table` WHERE $where LIKE '$like'";
        $data = $this->query($query);

        if ($data) {
            $return = array();
            while ($row = $data->fetch_assoc()) {
                $return[] = $row;
            }
            return $return;
        } else {
            return array();
        }
    }

    public function convertToInput($data)
    {
        return "'" . $data . "'";
    }
}

class position
{
    const ADMIN = 'admin';
    const MANAGER = 'manager';
    const EMPLOYEE = 'employee';
    const CUSTOMER = 'customer';
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

// Function to get headers, considering both apache and nginx
if (!function_exists('apache_request_headers')) {
    function apache_request_headers()
    {
        $return = array();
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) == "HTTP_") {
                $key = str_replace(" ", "-", ucwords(strtolower(str_replace("_", " ", substr($key, 5)))));
                $return[$key] = $value;
            } else {
                $return[$key] = $value;
            }
        }
        return $return;
    }
}

function checkAuth($position_need = null)
{
    global $dev;
    if ($dev) {
        return true;
    }
    if ($position_need == position::CUSTOMER) {
        return true;
    }
    $token = apache_request_headers()['Authorization'];
    if (!$token) {
        if ($position_need != null) {
            header("HTTP/1.1 401 Unauthorized");
            echo json_encode(array("status" => "error", "errorType" => "401", "msg" => "No access token. Access denied"));
            exit();
        } else {
            return false;
        }
    } else {
        $key = SECRET_KEY;
        $alg = array(ALGORITHM);
        try {
            $decoded = JWT::decode($token, $key, $alg);
            $decoded_array = (array)$decoded;
            $position_auth = $decoded_array['position'];

            if ($position_need == null) {
                return $decoded_array;
            } else {
                if ($position_need == position::ADMIN && $position_auth == position::ADMIN) {
                    return true;
                } else if ($position_need == position::MANAGER && ($position_auth == position::MANAGER || $position_auth == position::ADMIN)) {
                    return true;
                } else if ($position_need == position::EMPLOYEE && ($position_auth == position::MANAGER || $position_auth == position::EMPLOYEE || $position_auth == position::ADMIN)) {
                    return true;
                } else if ($position_need == position::CUSTOMER) {
                    return true;
                } else {
                    echo json_encode(array("status" => "error", "errorType" => "401", "msg" => "No enough permission, access denied"));
                    exit();
                }
            }
        } catch (Exception $ex) {
            if ($position_need != null) {
                header("HTTP/1.1 401 Unauthorized");
                echo json_encode(array("status" => "error", "errorType" => "401", "msg" => $ex->getMessage()));
                exit();
            } else {
                return false;
            }
        }

    }
}

function checkParameters($parameters)
{
    if (!is_array($parameters)) {
        if (!isset($_POST[$parameters])) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(array("status" => "error", "errorType" => "400", "msg" => "Parameters incorrect"));
            exit();
        }
    } else {
        foreach ($parameters as $param) {
            if (!isset($_POST[$param])) {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode(array("status" => "error", "errorType" => "400", "msg" => "Parameters incorrect"));
                exit();
            }
        }
    }

}

function badRequest()
{
    header("HTTP/1.1 400 Bad Request");
}