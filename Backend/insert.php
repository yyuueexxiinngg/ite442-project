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

// Check for token
$auth = checkAuth(position::ADMIN);

echo "Auth passed";

