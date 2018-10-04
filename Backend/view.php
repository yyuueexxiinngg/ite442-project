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

    public function getAllRepairID()
    {
        return $this->getAllRowWithQuery("SELECT repair_id FROM repair;");
    }

    public function getRepairOrder($repairID)
    {
        $repairID = $this->convertToInput($repairID);
        return $this->getAllRowWithQuery("SELECT * FROM get_repair_order WHERE repair_id=$repairID;")[0];
    }

    public function getView($view)
    {
        if ($view == 'init') {
            $result = array();
            $query = "SELECT date_sent_factory, date_arrive_company FROM product_in_repair";
            $result['dataSentFactory'] = $this->getAllRowWithQuery($query, "date_sent_factory");
            $result['dateArrivedCompany'] = $this->getAllRowWithQuery($query, "date_arrive_company");
            return $result;
        }

        if ($view == 'complain') {
            $query = "SELECT * FROM get_views_v WHERE Address = 'Complain';";
            return $this->getAllRowWithQuery($query);
        } elseif ($view == 'ส่งพี่พงษ์2') {

            $date = $_POST['dateSentFactory'];
            $query = "SELECT * FROM get_views_v WHERE date_sent_factory = '$date';";
            return $this->getAllRowWithQuery($query);
        } elseif ($view == 'OutsourceRepair') {

            $date = $_POST['dateArrivedCompany'];
            $query = "SELECT * FROM get_views_v WHERE date_arrive_company = '$date';";
            return $this->getAllRowWithQuery($query);
        } elseif ($view == 'FollowUpReport') {
            return $this->getAllRowWithQuery("SELECT * FROM get_views_v");
        }

    }

    public function getInProgress()
    {
        $query = "SELECT * FROM get_in_progress_v";

        return $this->getAllRowWithQuery($query);
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
        break;
    case 'repairOrder':
        if (!isset($_POST['get'])) {
            echo json_encode($view->getAllRepairID());
        } else {
            echo json_encode($view->getRepairOrder($_POST['get']));
        }
        break;
    case 'views':
        if (isset($_POST['view'])) {
            echo json_encode($view->getView($_POST['view']));
        }
        break;
}


$view->close();
//$repair_id = $_POST["id"];
// Start to init the program
