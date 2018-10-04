-- receipt view

CREATE VIEW `receipt_v` AS
    SELECT
        RC.pro_number,
        C.customername name,
        D.deptname department_name,
        RP.repair_id,
        RP.repair_form,
        P.productcode product_code,
        P.description,
        RC.cost,
        DATE_FORMAT(RC.date, "%d/%m/%Y") date
    FROM
        receipt RC
            LEFT JOIN
        repair RP ON RP.pro_number = RC.pro_number
            LEFT JOIN
        customer C ON C.customer_id = RP.cust_id
            LEFT JOIN
        department D ON D.department_id = RP.dept_id
            LEFT JOIN
        product_in_repair PR ON PR.repair_id = RP.repair_id
            LEFT JOIN
        product P ON P.productcode = PR.prod_code;

-- usage

SELECT
    *
FROM
    receipt_v
WHERE
    pro_number = 'PR18/001';


-- End of receipt view

--  New repair id view inorder to create formatted primary key

CREATE VIEW `new_repair_id_v` AS
    SELECT
        CONCAT(DATE_FORMAT(NOW(), '%y'),'/',RIGHT(1000 + MAX(SUBSTRING(repair_id, 4)) + 1,3)) NewRepairID
    FROM
        repair
    WHERE
        repair_id LIKE CONCAT(DATE_FORMAT(NOW(), '%y'), '%');

-- enc of new repair id view


-- View for get repair form for updating

CREATE VIEW `get_repair_form_v` AS
    SELECT
        R.repair_id repairID,
        R.repair_form,
        R.cust_id,
        C.customertel,
        R.dept_id,
        RC.pro_number,
        IFNULL(RC.cost, 0) cost,
        RC.date,
        R.repair_details,
        PIR.*,
        W.warranty_type
    FROM
        repair R
            LEFT JOIN
        customer C ON R.cust_id = C.customer_id
            LEFT JOIN
        product_in_repair PIR ON PIR.repair_id = R.repair_id
            LEFT JOIN
        warranty W ON PIR.warranty_id = W.warranty_id
            LEFT JOIN
        receipt RC ON R.pro_number = RC.pro_number;

-- Uasge

SELECT
    *
FROM
    get_repair_form_v
WHERE
    repair_id = '18/002';

-- End of get repair form


-- In Progress

SELECT
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
    PIR.date_received_factory IS NULL;

-- End of in progress