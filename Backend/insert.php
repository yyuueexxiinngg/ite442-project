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

    public function createForm($data)
    {
//        error_reporting(0);
        $newRepairID = $this->convertToInput($this->getAllRow('new_repair_id_v')[0]['NewRepairID']);

        $department = $this->convertToInput($data->department);
        $productCode = $this->convertToInput($data->productCode);
        $warranty = $this->convertToInput($data->warranty->warranty_id);
        $repairLocation = $this->convertToInput($data->repairLocation);

        $repairForm = $data->repairForm ? $this->convertToInput($data->repairForm) : "";
        $repairDetail = $data->repairDetail ? $this->convertToInput($data->repairDetail) : "";
        $shippingMethod = $data->shippingMethod ? $this->convertToInput($data->shippingMethod) : "";
        $personSent = $data->personSent ? $this->convertToInput($data->personSent) : "";
        $trackingCode = $data->trackingCode ? $this->convertToInput($data->trackingCode) : "";

        $purchaseDate = $data->dates->purchaseDate ? $this->convertToInput($data->dates->purchaseDate) : "";
        $receiveFromCustomer = $data->dates->receiveFromCustomer ? $this->convertToInput($data->dates->receiveFromCustomer) : "";
        $dateArrivedCompany = $data->dates->dateArrivedCompany ? $this->convertToInput($data->dates->dateArrivedCompany) : "";
        $dateSentFactory = $data->dates->dateSentFactory ? $this->convertToInput($data->dates->dateSentFactory) : "";
        $dateReceiveFromFactory = $data->dates->dateReceiveFromFactory ? $this->convertToInput($data->dates->dateReceiveFromFactory) : "";
        $dateReturnToStore = $data->dates->dateReturnToStore ? $this->convertToInput($data->dates->dateReturnToStore) : "";

//        echo json_encode($data);
        $CustomerID = $this->convertToInput("5");
        $proNumber = "";
        if ($data->newCustomer) {
            $customerName = $data->customer->customerName;
            $customerTel = $data->customer->customerTel;

            $CustomerID = $this->convertToInput($this->createCustomer($customerName, $customerTel));
        } else {
            $CustomerID = $this->convertToInput($data->customer);
        }

        if ($data->newReceipt) {
            $proNumber = $data->receipt->proNumber;
            $cost = $data->cost;
            $date = $data->receipt->date;

            $this->createReceipt($proNumber, $cost, $date);

            $proNumber = $this->convertToInput($proNumber);
        } else {
            $proNumber = "";
            $cost = "";
        }

        $queryToRepair = "INSERT INTO `repair` (`repair_id`, `cust_id`, `dept_id`, `pro_number`, `repair_form`, `repair_details`)" .
            " VALUES ($newRepairID, $CustomerID, $department, $proNumber, $repairForm, $repairDetail);";

        if (!$this->query($queryToRepair)) {
            echo json_encode(array("status" => "error", "errorType" => "400", "msg" => "Failed to create Repair"));
            return;
        }


        $queryToProductInRepair = "INSERT INTO `product_in_repair` (`repair_id`, `prod_code`, `warranty_id`, `date_purchased`, `date_rec_store`, `repair_loc`, `date_arrive_company`, `date_sent_factory`, `date_received_factory`, `date_returned_store`, `send_method`, `person_sent`, `tracking_code`) " .
            "VALUES ($newRepairID, $productCode, $warranty, $purchaseDate, $receiveFromCustomer," .
            "$repairLocation, $dateArrivedCompany, $dateSentFactory, $dateReceiveFromFactory," .
            " $dateReturnToStore, $shippingMethod, $personSent, $trackingCode);";

        if (!$this->query($queryToProductInRepair)) {
            echo json_encode(array("status" => "error", "errorType" => "400", "msg" => "Failed to create Product in Repair"));
            return;
        }

        echo json_encode(array("status" => "success", "code" => "200", "msg" => "Successfully create new repair form!"));
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
} elseif (file_get_contents('php://input')) {
    if (json_decode(file_get_contents('php://input'))->create) {
        $data = json_decode(file_get_contents('php://input'));
        $insert->createForm($data);
    }
}


