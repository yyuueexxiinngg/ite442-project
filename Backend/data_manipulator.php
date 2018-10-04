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

class DataManipulator extends mysql_helper
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

        $result['departments'] = $this->getAllRow('department');
        $result['products'] = $this->getAllRow('product', ['productcode']);
        $result['repair_location'] = $this->getAllRow('repair_location');
        $result['warranty'] = $this->getAllRow('warranty');
        $result['shipping_method'] = $this->getAllRow('shipping_method');
        return $result;
    }

    public function updateInit()
    {
        $result = array();
        $result = $this->getAllRowWithQuery("SELECT repair_id, customername, customertel,repair_form, IFNULL(cost,0) cost FROM repair R LEFT JOIN customer C ON R.cust_id = C.customer_id LEFT JOIN receipt RC ON RC.pro_number = R.pro_number; ");
        return $result;
    }

    public function getRepairForm($repairID)
    {
        $query = "SELECT * FROM get_repair_form_v WHERE repair_id = '$repairID';";
        $result = $this->getAllRowWithQuery($query);
        return $result[0];
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

    public function deleteRepairForm($data)
    {
        $repairID = $this->convertToInput($data->repairID);
        $query = "DELETE FROM `product_in_repair` WHERE `repair_id`=$repairID;";
        if (!$this->query($query)) {
            echo json_encode(array("status" => "error", "errorType" => "400", "msg" => "Failed to Delete Product in Repair"));
            return;
        }

        $query = "DELETE FROM `ite442_project`.`repair` WHERE `repair_id`=$repairID;";
        if (!$this->query($query)) {
            echo json_encode(array("status" => "error", "errorType" => "400", "msg" => "Failed to Delete Repair"));
            return;
        }

        echo json_encode(array("status" => "success", "code" => "200", "msg" => "Successfully delete repair form!"));
    }

    public function updateOrCreateForm($data, $create = false)
    {
//        error_reporting(0);
        if ($create)
            $repairID = $this->convertToInput($this->getAllRow('new_repair_id_v')[0]['NewRepairID']);
        else
            $repairID = $this->convertToInput($data->repairID);

        $department = $this->convertToInput($data->department);
        $productCode = $this->convertToInput($data->productCode);
        $warranty = $this->convertToInput($data->warranty->warranty_id);
        $repairLocation = $this->convertToInput($data->repairLocation);

        $repairForm = $data->repairForm ? $this->convertToInput($data->repairForm) : "NULL";
        $repairDetail = $data->repairDetail ? $this->convertToInput($data->repairDetail) : "NULL";
        $shippingMethod = $data->shippingMethod ? $this->convertToInput($data->shippingMethod) : "NULL";
        $personSent = $data->personSent ? $this->convertToInput($data->personSent) : "NULL";
        $trackingCode = $data->trackingCode ? $this->convertToInput($data->trackingCode) : "NULL";

        $purchaseDate = $data->dates->purchaseDate ? $this->convertToInput($data->dates->purchaseDate) : "NULL";
        $receiveFromCustomer = $data->dates->receiveFromCustomer ? $this->convertToInput($data->dates->receiveFromCustomer) : "NULL";
        $dateArrivedCompany = $data->dates->dateArrivedCompany ? $this->convertToInput($data->dates->dateArrivedCompany) : "NULL";
        $dateSentFactory = $data->dates->dateSentFactory ? $this->convertToInput($data->dates->dateSentFactory) : "NULL";
        $dateReceiveFromFactory = $data->dates->dateReceiveFromFactory ? $this->convertToInput($data->dates->dateReceiveFromFactory) : "NULL";
        $dateReturnToStore = $data->dates->dateReturnToStore ? $this->convertToInput($data->dates->dateReturnToStore) : "NULL";

        if ($data->newCustomer) {
            $customerName = $data->customer->customerName;
            $customerTel = $data->customer->customerTel;

            $customerID = $this->convertToInput($this->createCustomer($customerName, $customerTel));
        } else {
            $customerID = $this->convertToInput($data->customer);
        }

        if ($data->newReceipt) {
            if ($create) {
                $proNumber = $data->receipt->proNumber;
                $cost = $data->cost;
                $date = $data->receipt->date;

                $this->createReceipt($proNumber, $cost, $date);

                $proNumber = $this->convertToInput($proNumber);
            } else {
                $proNumber = $this->convertToInput($data->receipt->proNumber);
                $cost = $this->convertToInput($data->cost);
                $date = $this->convertToInput($data->receipt->date);

                $queryToReceipt = "UPDATE `receipt` SET `pro_number`=$proNumber, `cost`=$cost, `date`=$date WHERE `pro_number`=$proNumber;";

                if (!$this->query($queryToReceipt)) {
                    echo json_encode(array("status" => "error", "errorType" => "400", "msg" => "Failed to update receipt"));
                    return;
                }
            }
        } else {
            $proNumber = "NULL";
            $cost = "NULL";
        }

        if ($create) {
            $queryToRepair = "INSERT INTO `repair` (`repair_id`, `cust_id`, `dept_id`, `pro_number`, `repair_form`, `repair_details`)" .
                " VALUES ($repairID, $customerID, $department, $proNumber, $repairForm, $repairDetail);";

            if (!$this->query($queryToRepair)) {
                echo json_encode(array("status" => "error", "errorType" => "400", "msg" => "Failed to create Repair"));
                return;
            }


            $queryToProductInRepair = "INSERT INTO `product_in_repair` (`repair_id`, `prod_code`, `warranty_id`, `date_purchased`, `date_rec_store`, `repair_loc`, `date_arrive_company`, `date_sent_factory`, `date_received_factory`, `date_returned_store`, `send_method`, `person_sent`, `tracking_code`) " .
                "VALUES ($repairID, $productCode, $warranty, $purchaseDate, $receiveFromCustomer," .
                "$repairLocation, $dateArrivedCompany, $dateSentFactory, $dateReceiveFromFactory," .
                " $dateReturnToStore, $shippingMethod, $personSent, $trackingCode);";

            if (!$this->query($queryToProductInRepair)) {
                echo json_encode(array("status" => "error", "errorType" => "400", "msg" => "Failed to create Product in Repair"));
                return;
            }

            echo json_encode(array("status" => "success", "code" => "200", "msg" => "Successfully created new repair form!"));
        } else {
            $queryToRepair = "UPDATE `repair` SET `repair_id`=$repairID, " .
                "`cust_id`=$customerID, `dept_id`=$department, `pro_number`=$proNumber, " .
                "`repair_form`=$repairForm, `repair_details`=$repairDetail" .
                "WHERE `repair_id`=$repairID;";

            if (!$this->query($queryToRepair)) {
                echo json_encode(array("status" => "error", "errorType" => "400", "msg" => "Failed to update Repair"));
                return;
            }


            $queryToProductInRepair = "UPDATE `product_in_repair` SET `repair_id`=$repairID," .
                " `prod_code`=$productCode, `warranty_id`=$warranty, `date_purchased`=$purchaseDate," .
                " `date_rec_store`=$receiveFromCustomer, `repair_loc`=$repairLocation, `date_arrive_company`=$dateArrivedCompany, " .
                "`date_sent_factory`=$dateSentFactory, `date_received_factory`=$dateReceiveFromFactory, `date_returned_store`=$dateReturnToStore," .
                " `send_method`=$shippingMethod, `person_sent`=$personSent, `tracking_code`=$trackingCode" .
                " WHERE `repair_id`=$repairID;";

            if (!$this->query($queryToProductInRepair)) {
                echo json_encode(array("status" => "error", "errorType" => "400", "msg" => "Failed to update Product in Repair"));
                return;
            }

            echo json_encode(array("status" => "success", "code" => "200", "msg" => "Successfully updated repair form data!"));
        }

    }

    private function createCustomer($customerName, $customerTel)
    {
        $query = "INSERT INTO `customer` (`customername`, `customertel`) VALUES ('$customerName', '$customerTel');";

        if (!$this->query($query)) {
            echo json_encode(array("status" => "error", "errorType" => "400", "msg" => "Failed to create new customer"));
            return;
        }

        $newCustomerID = $this->conn->insert_id;

        return $newCustomerID;
    }

    private function createReceipt($proNumber, $cost, $date)
    {
        $query = "INSERT INTO `receipt` (`pro_number`, `cost`, `date`) VALUES ('$proNumber', '$cost', '$date');";
        if (!$this->query($query)) {
            echo json_encode(array("status" => "error", "errorType" => "400", "msg" => "Failed to create new receipt"));
            return;
        }
    }
}

$dev = true;

// Check for token
$auth = checkAuth(position::EMPLOYEE);

$dm = new DataManipulator();

if (isset($_POST['request'])) {
    switch ($_POST['request']) {
        case "init":
            echo json_encode($dm->init());
            break;
        case "customer";
            if (isset($_POST['customerName'])) {
                echo json_encode($dm->searchCustomerByName($_POST['customerName']));
            } elseif (isset($_POST['customerTel'])) {
                echo json_encode($dm->searchCustomerByTel($_POST['customerTel']));
            }
            break;
        case "updateInit":
            echo json_encode($dm->updateInit());
            break;
        case "getRepairForm":
            echo json_encode($dm->getRepairForm($_POST['repairID']));
            return;
    }
} elseif (file_get_contents('php://input')) {
    if (json_decode(file_get_contents('php://input'))) {
        $data = json_decode(file_get_contents('php://input'));
        if ($data->create)
            $dm->updateOrCreateForm($data, true);
        elseif ($data->update)
            $dm->updateOrCreateForm($data);
        elseif ($data->delete)
            $dm->deleteRepairForm($data);
    }
}

$dm->close();


