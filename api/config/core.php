<?php
    // SHOW ERROR REPORTING
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    // HOME PAGE URL
    $home_url="http://localhost:8080/api/";

    // GET PAGE FROM URL , DEFAULT = 1
    $page = isset($_GET['page']) ? $_GET['page'] : 1;

    // SET RECORDS PER PAGE
    $records_per_page = 1;

    // CALCULATE QUERY LIMIT
    $from_record_num = ($records_per_page * $page) - $records_per_page;
?>