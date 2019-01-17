<?php
    // REQUIRED HEADERS
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    // INCLUDE DATABASE AND OBJECT FILES
    include_once '../config/database.php';
    include_once '../objects/user.php';

    // INSTANTIATE DATABASE
    $database = new Database();
    $db       = $database->getConnection();

    // INSTANTIATE OBJECT
    $user = new User($db);

    // GET POSTED DATA
    $data = json_decode(file_get_contents("php://input"));

    // MAKE SURE DATA IS NOT EMPTY
    if(
        !empty($data->user_email) &&
        !empty($data->user_name) &&
        !empty($data->user_contact) &&
        !empty($data->user_password) &&
        !empty($data->user_url)
    ){
        // SET DATA
        $user->user_email    = $data->user_email;
        $user->user_name     = $data->user_name;
        $user->user_contact  = $data->user_contact;
        $user->user_password = $data->user_password;
        $user->user_url      = $data->user_url;
        $data_arr            = array();

        // TRY TO CREATE
        if($user->insert()){

            $data_arr["status" ]  = true;
            $data_arr["message"]  = "DATA INSETED";
            $data_arr["code"   ]  = 201;
            $data_arr["data"   ]  = array();

            // SET RESPONSE CODE (201 INSERTED)
            http_response_code(201);

            // SHOW SUCCESS MESSAGE IN JSON FORMAT
            echo json_encode($data_arr);
        }

        // IF UNABLE TO CREATE
        else{

            $data_arr["status" ]  = false;
            $data_arr["message"]  = "UNABLE TO INSERT DATA";
            $data_arr["code"   ]  = 503;
            $data_arr["data"   ]  = array();

            // SET RESPONSE CODE (503 SERVICE UNAVAILABLE)
            http_response_code(503);

            // SHOW ERROR MESSAGE IN JSON FORMAT
            echo json_encode($data_arr);
        }
    }

    // DATA IS INCOMPLETE
    else{

        $data_arr["status" ]  = false;
        $data_arr["message"]  = "REQUEST DATA INCOMPLETE";
        $data_arr["code"   ]  = 400;
        $data_arr["data"   ]  = array();

        // SET RESPONSE CODE (400 BAD REQUEST)
        http_response_code(400);

        // SHOW ERROR MESSAGE IN JSON FORMAT
        echo json_encode($data_arr);
    }
?>