<?php
    // REQUIRED HEADERS
    // header("Access-Control-Allow-Origin: *");
    // header("Access-Control-Allow-Headers: access");
    // header("Access-Control-Allow-Methods: GET");
    // header("Access-Control-Allow-Credentials: true");
    // header('Content-Type: application/json');
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
    $db = $database->getConnection();

    // INSTANTIATE OBJECT
    $user = new User($db);

    // GET POSTED DATA
    $data = json_decode(file_get_contents("php://input"));

    // INSTANTIATE DATA ARRAY
    $data_arr = array();

    if(!empty(($data->user_email))){

        // SET DATA
        $user->user_email = $data->user_email;

        // DATA OF PRIMARY KEY AVAILABLE
        if($user->readOne()){

            // CREATE DATA ARRAY
            $data_item = array(
                "user_email" =>  $user->user_email,
                "user_name" =>  $user->user_name,
                "user_contact" => $user->user_contact,
                "user_password" => $user->user_password,
                "user_url" => $user->user_url
            );

            $data_arr["status" ]  = true;
            $data_arr["message"]  = "DATA AVAILABLE";
            $data_arr["code"   ]  = 200;
            $data_arr["data"   ]  = array();

            array_push($data_arr["data"], $data_item);

            // SET RESPONSE CODE (200 OK)
            http_response_code(200);

            // SEND RESPONSE
            echo json_encode($data_arr);

        }
        // DATA OF PRIMARY KEY UNAVAILABLE
        else{
            $data_arr["status" ]  = false;
            $data_arr["message"]  = "NO DATA AVAILABLE";
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

        // SET RESPONSE CODE (404 NOT FOUND)
        http_response_code(404);

        // SHOW ERROR MESSAGE IN JSON FORMAT
        echo json_encode($data_arr);
    }
?>