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

    // INSTANTIATE OBJECT
    $database = new Database();
    $db       = $database->getConnection();

    // INSTANTIATE OBJECT
    $user = new User($db);

    // GET POSTED DATA
    $data = json_decode(file_get_contents("php://input"));

    // SET PRIMARY KEY OF DATA WITCH WILL BE DELETED
    $user->user_email = $data->user_email;

    $data_arr = array();

    // INPUT DATA COMPLETE
    if(!empty($data->user_email)){

        // DATA OF PRIMARY KEY AVAILABLE
        if($user->check()){

            // TRY TO DELETE
            if($user->delete()){

                $data_arr["status" ]  = true;
                $data_arr["message"]  = "DATA DELETED";
                $data_arr["code"   ]  = 200;
                $data_arr["data"   ]  = array();

                // SET RESPONSE CODE (200 OK)
                http_response_code(200);

                // SHOW SUCCESS MESSAGE IN JSON FORMAT
                echo json_encode($data_arr);
            }

            // IF UNABLE TO DELETE
            else{
                $data_arr["status" ]  = false;
                $data_arr["message"]  = "UNABLE TO DELETE";
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
            $data_arr["message"]  = "NO DATA AVAILABLE TO DELETE";
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