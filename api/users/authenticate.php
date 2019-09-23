<?php

/**
 * This file contains functionalities to authenticate 
 * a user using email and password
 *
 * @package    Authentication_API
 * @subpackage Authentication_API/api/users
 */

// headers
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');
header('Access-Contro-Allow-Methods: POST');  
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-type,Access-Contro-Allow-Methods,Authorization,X-Requested-Width');  

// includes
require_once '../../config/database.php';
require_once '../../models/user.php';

// instantiate database & connect
$database = new Database();

// connect is user defined function
$db = $database->connect();

// intantiate user object
$user = new User($db);

// get raw posted data
$data = json_decode(file_get_contents("php://input"));

// check if vars are empty
if ($data->email == '' || $data->password == '') {

    // prepare and echo json output
    echo json_encode(
        array(
            'response code' => '400',
            'message'       => 'Bad request, Email or Password field can\'t be empty.'
        )
    );

    die();
}

$user->email = $data->email;
$user->password = $data->password;

// Register user
$response = $user->authenticate();

if ($response === true) {
  
    // user successfully authenticated
    echo json_encode(
        array(
            'id'            => $user->id,
            'response code' => '200',
            'message'       => 'Login successful'
        )
    );

} elseif ($response === '404') {

    // user is not authenticated
    echo json_encode(
        array(
            'response code' => '404',
            'message'       => 'Incorrect username or password'
            )
    );

} else {

    // internal error
    echo json_encode(
        array(
            'response code' => '500',
            'message'       => 'Something went wrong.'
            )
    );

}