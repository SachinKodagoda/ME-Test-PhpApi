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
    $db = $database->getConnection();

    // INSTANTIATE OBJECT
    $user = new User($db);

    // GET DATA FROM REQUEST
    $data = json_decode(file_get_contents("php://input"));

    // SET PRIMARY KEY
    $user->user_email = $data->user_email;

    // SET VALUEST TO BE UPDATED
    $user->user_name = $data->user_name;
    $user->user_contact = $data->user_contact;
    $user->user_password = $data->user_password;
    $user->user_url = $data->user_url;

    // INSTANTIATE DATA ARRAY
    $data_arr = array();

    // INPUT DATA COMPLETE
    if(
        !empty($data->user_email) &&
        !empty($data->user_name) &&
        !empty($data->user_contact) &&
        !empty($data->user_password) &&
        !empty($data->user_url)
    ){
        // DATA OF PRIMARY KEY AVAILABLE
        if($user->check()){

            // TRY TO UPDATE
            if($user->update()){

                $data_arr["status" ]  = true;
                $data_arr["message"]  = "DATA UPDATED";
                $data_arr["code"   ]  = 200;
                $data_arr["data"   ]  = array();

                // SET RESPONSE CODE (200 OK)
                http_response_code(200);

                // SHOW SUCCESS MESSAGE IN JSON FORMAT
                echo json_encode($data_arr);

            }
            // UNABLE TO UPDATE BECAUSE SERVER ERROR
            else{

                $data_arr["status" ]  = false;
                $data_arr["message"]  = "UNABLE TO UPDATE";
                $data_arr["code"   ]  = 503;
                $data_arr["data"   ]  = array();

                // SET RESPONSE CODE (503 SERVICE UNAVAILABLE)
                http_response_code(503);

                // SHOW ERROR MESSAGE IN JSON FORMAT
                echo json_encode($data_arr);
            }

        }
        // DATA OF PRIMARY KEY UNAVAILABLE
        else{
            $data_arr["status" ]  = false;
            $data_arr["message"]  = "NO DATA AVAILABLE TO UPDATE";
            $data_arr["code"   ]  = 400;
            $data_arr["data"   ]  = array();

            // SET RESPONSE CODE (400 BAD REQUEST)
            http_response_code(400);

            // SHOW ERROR MESSAGE IN JSON FORMAT
            echo json_encode($data_arr);
        }

    }
    // INPUT DATA NOT COMPLETE
    else{

        $data_arr["status" ]  = false;
        $data_arr["message"]  = "BAD REQUEST";
        $data_arr["code"   ]  = 400;
        $data_arr["data"   ]  = array();

        // SET RESPONSE CODE (400 BAD REQUEST)
        http_response_code(400);

        // SHOW ERROR MESSAGE IN JSON FORMAT
        echo json_encode($data_arr);

    }




?>