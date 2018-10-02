<?php
/**
 * Api for inserting data
 * User: yyuueexxiinngg
 * Date: 2018/9/10
 * Time: 11:44
 */
require dirname(__FILE__) . '/backend_helper.php';
// Temporary disable error reporting for dev environment
//error_reporting(0);

class Insert extends mysql_helper
{
    public function __construct()
    {
        try {
            parent::__construct();
        } catch (Exception $ex) {
            echo "There is a sweet exception occurs! <br/>" . $ex;
        }

    }

    public function init()
    {
        $result = array();

        $result['departments'] = $this->getAllRow('department');;
        $result['products'] = $this->getAllRow('product', ['productcode']);;
        $result['repair_location'] = $this->getAllRow('repair_location');;
        $result['warranty'] = $this->getAllRow('warranty');;
        $result['shipping_method'] = $this->getAllRow('shipping_method');;
        return $result;
    }

    public function searchCustomerByName($name)
    {
        $like = '%' . trim($name) . '%';
        $result = $this->getRowsLike('customer', 'customername', $like);
        return $result;
    }

    public function searchCustomerByTel($tel)
    {
        $like = '%' . trim($tel) . '%';
        $result = $this->getRowsLike('customer', 'customertel', $like);
        return $result;
    }
}

$dev = true;

// Check for token
$auth = checkAuth(position::EMPLOYEE);

$insert = new Insert();

if (isset($_POST['request'])) {
    switch ($_POST['request']) {
        case "init":
            echo json_encode($insert->init());
        case "customer";
            if (isset($_POST['customerName'])) {
                echo json_encode($insert->searchCustomerByName($_POST['customerName']));
            } elseif (isset($_POST['customerTel'])) {
                echo json_encode($insert->searchCustomerByTel($_POST['customerTel']));
            }
    }
}


