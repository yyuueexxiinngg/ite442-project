<?php
/**
 * Api for getting data
 * User: yyuueexxiinngg
 * Date: 2018/9/10
 * Time: 11:43
 */
require dirname(__FILE__) . '/backend_helper.php';

// Create abstrac class for teamwork
abstract class AbstractView extends mysql_helper
{
    // Function to show teammates. Would remove for actual development
    abstract protected function getFuncDemo();

    abstract protected function getReceipt();

    // Init parent constructor to connect to the DB
    public function __construct()
    {
        try {
            parent::__construct();
        } catch (Exception $ex) {
            echo "There is a sweet exception occurs! <br/>" . $ex;
        }

    }
}

class View extends AbstractView
{

    /**
     * Get receipt info from receipt view
     * @param request $_POST ['request'] What is the request for
     * @param pro_number $_POST ['pro_number'] pro_number requset bt receipt_v
     * @return array of first matching row
     */
    public function getReceipt()
    {
        checkAuth(position::CUSTOMER);
        checkParameters('pro_number');
        $pro_number = $_POST['pro_number'];
        $pro_number = mysqli_real_escape_string($this->conn, $pro_number);
        $query = "SELECT * FROM receipt_v WHERE pro_number = '$pro_number'";
        $data = $this->query($query);

        if ($data) {
            return $data->fetch_assoc();
        } else {
            badRequest();
            return array("status" => "error", "errorType" => "400", "msg" => "No such pro number");
        }
    }

    public function getInProgress()
    {
        $qurey= "SELECT 
                    R.repair_id,
                    PIR.prod_code,
                    W.warranty_type,
                    RL.Address repair_loc,
                    PIR.send_method,
                    PIR.person_sent,
                    DATEDIFF(NOW(),PIR.date_rec_store) day_past
                FROM
                    ite442_project.repair R
                        LEFT JOIN
                    product_in_repair PIR ON R.repair_id = PIR.repair_id
                        LEFT JOIN
                    warranty W ON PIR.warranty_id = W.warranty_id
                        LEFT JOIN
                    repair_location RL ON PIR.repair_loc = RL.repair_loc_id
                WHERE
                    PIR.date_received_factory IS NULL;";

        return $this->getAllRowWithQuery($qurey);
    }

    public function getFuncDemo()
    {
        $query = "SELECT * FROM employee";

        // Use $this->query() instead of $conn->query()! Already handled 0 num_rows situation
        $result = $this->query($query);

        $rows = array();
        // Whenever there is still new row, put it to $rows array
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }
}

// Change this to true if you are just testing
$dev = false;

if ($dev) {
    $_POST['request'] = 'receipt';
    $_POST['pro_number'] = 'PR18/002';
}

checkParameters('request');

$request = $_POST['request'];
$view = new View();

switch ($request) {
    case 'receipt':
        echo json_encode($view->getReceipt());
        break;
    case 'inProgress':
        echo json_encode($view->getInProgress());
}


$view->close();
//$repair_id = $_POST["id"];
// Start to init the program
