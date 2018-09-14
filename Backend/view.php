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

    abstract protected function getReceipt($repair_id);

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
    public function getReceipt($repair_id)
    {
        // TODO: Implement getReceipt() method.
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


// Get input repair form id
//$repair_id = $_POST["id"];
// Start to init the program
$view = new View();

var_dump($view->getFuncDemo());

$view->close();