<?php
/**
 * Api for login
 * User: yyuueexxiinngg
 * Date: 2018/9/10
 * Time: 11:42
 */

require dirname(__FILE__) . '/backend_helper.php';
use \Firebase\JWT\JWT;
class Login extends mysql_helper
{
    public function __construct()
    {
        try {
            parent::__construct();
        } catch (Exception $ex) {
            echo "There is a sweet exception occurs! <br/>" . $ex;
        }
    }

    public function login($username, $password, $phone = null, $email = null)
    {
        // Prevent SQL injection with space
        $username = mysqli_real_escape_string($this->conn, $username);
        $query = "SELECT * FROM employee WHERE username='$username'";

        $data = $this->query($query);


        if ($data) {
            $result = array("status" => "success");

            $row = $data->fetch_assoc();
            if ($password === $row['password']) {
                $result["userInfo"]['username'] = $row['username'];
                $result["userInfo"]['email'] = $row['e-mail'];
                $result["userInfo"]['position'] = $row['position'];

                return $result;
            } else {
                return array("status" => "error", "errorType" => "1", "msg" => "Password incorrect");
            }
        } else {
            return array("status" => "error", "errorType" => "0", "msg" => "User not found");
        }
    }
}

$username = $_POST['username'];
$password = $_POST['password'];

$login = new Login();
$result = $login->login($username, $password);
if ($result['status'] == 'success') {
    $issuedAt = time();
    $expirationTime = $issuedAt + 600;
    $payload = array(
        'username' => $result["userInfo"]['username'],
        'position' => $result["userInfo"]['position'],
        'iat' => $issuedAt,
        'exp' => $expirationTime
    );
    $key = SECRET_KEY;
    $alg = ALGORITHM;
    $jwt = JWT::encode($payload, $key, $alg);

    $result['token'] = $jwt;
    echo json_encode($result);
} else if ($result['status'] == 'error') {
    echo json_encode($result);
}

$login->close();
