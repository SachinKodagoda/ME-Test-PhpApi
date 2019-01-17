<?php
    // REQUIRED HEADERS
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    // INCLUDE DATABASE AND OBJECT FILES
    include_once '../config/core.php';
    include_once '../shared/utilities.php';
    include_once '../config/database.php';
    include_once '../objects/user.php';

    // INSTANTIATE DATABASE
    $database = new Database();
    $db = $database->getConnection();

    // INSTANTIATE OBJECTS
    $user = new User($db);
    $utilities = new Utilities();

    // QUERY DATA
    $stmt = $user->readPaging($from_record_num, $records_per_page);

    // GET RECORDS COUNT
    $num = $stmt->rowCount();

    // INSTANTIATE DATA ARRAY
    $data_arr = array();

    // IF MORE THAN 0 RECORDS FOUND
    if($num>0){
        $data_arr["status" ]  = true;
        $data_arr["message"]  = "DATA AVAILABLE";
        $data_arr["code"   ]  = 200;
        $data_arr["data"   ]  = array();
        $data_arr["paging" ]  = array();

        // RETRIEVE TABLE CONTENTS
        // fetch() is faster than fetchAll()
        // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

            // EXTRACT ROW MAKES $row['name'] TO $name
            extract($row);

            $data_item = array(
                "user_email" => $user_email,
                "user_name" => $user_name,
                "user_contact" => $user_contact,
                "user_created" => $user_created
            );

            // html_entity_decode --> convert html entities to applicable characters.
            array_push($data_arr["data"], $data_item);
        }

        // INCLUDE PAGING
        $total_rows = $user->count();
        $page_url = "{$home_url}user/paging.php?";
        $paging = $utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
        $data_arr["paging"] = $paging;

        // SET RESPONSE CODE (200 OK)
        http_response_code(200);

        // SHOW OBJECT DATA IN JSON FORMAT
        echo json_encode($data_arr);
    }else{

        $data_arr["status" ]  = false;
        $data_arr["message"]  = "NO DATA FOUND";
        $data_arr["code"   ]  = 400;
        $data_arr["data"   ]  = array();


        // SET RESPONSE CODE (404 NOT FOUND)
        http_response_code(404);

        // SHOW THAT NO DATA FOUND
        echo json_encode($data_arr);

    }
?>