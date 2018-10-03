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