<?php

/**
 * This file contains the functionalities to delete a user
 *
 * @package    Authentication_API
 * @subpackage Authentication_API/api/users
 */

// headers
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');  
header('Access-Contro-Allow-Methods: DELETE');  
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-type,Access-Contro-Allow-Methods,Authorization,X-Requested-Width');  

// includes
require_once '../../config/database.php';
require_once '../../models/user.php';

// instantiate database & connect
$database = new Database();

// connect is user defined function
$db = $database -> connect();

// intantiate blog post object
$user = new User($db);

// get raw posted data
$data = json_decode(file_get_contents("php://input"));

// check for any missing data
if ($data->id == '' || $data->email == '') {

    echo json_encode(
        array(
            'response code' => '400',
            'message'       => 'User could not be removed, `id` or email field can\'t be empty.'
        )
    );

    die();
}

// set ID to update
$user->id = $data->id;
$user->email = $data->email;

// Delete post
if ($user->delete()) {

    echo json_encode(
        array(
            'response code' => '200',
            'message'       => 'User removed!'
            )
    );

} else {
    
    echo json_encode(
        array(
            'response code' => '304',
            'message' => 'User could not be removed'
            )
    );

}