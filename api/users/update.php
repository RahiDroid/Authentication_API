<?php

/**
 * This file contains the functionalities to change the user 
 * password
 *
 * @package    Authentication_API
 * @subpackage Authentication_API/api/users
 */

// headers
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');  
header('Access-Contro-Allow-Methods: PUT');  
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-type,Access-Contro-Allow-Methods,Authorization,X-Requested-Width');  


require_once '../../config/database.php';
require_once '../../models/user.php';

// instantiate database & connect
$database = new Database();

// connect is user defined function
$db = $database -> connect();

// intantiate blog user object
$user = new User($db);

// get raw user input data
$data = json_decode(file_get_contents("php://input"));

// check for any missing data
if ($data->id == '' || $data->email == '' || $data->passsword) {
    echo json_encode(
        array(
            'response code' => '400',
            'message'       => 'User could not be removed, id, email or password field can\'t be empty.'
        )
    );

    die();
}

// set ID to update
$user->id = $data->id;
$user->email = $data->email;
$user->password = $data->password;

// update user
if ($user->update()) {
    echo json_encode(
        array(
            'response code' => '200',
            'message'       => 'Data Updated!'
        )
    );
} else {
    echo json_encode(
        array(
            'response code' => '304',
            'message'       => 'Data Not Updated'
        )
    );
}