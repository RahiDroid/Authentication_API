<?php

/**
 * Table structure: 
 * 
 * CREATE TABLE `rest_api_db`.`users` ( 
 * `id` INT(11) NOT NULL AUTO_INCREMENT , 
 * `email` VARCHAR(320) NOT NULL , 
 * `password` VARCHAR(60) NOT NULL , 
 * PRIMARY KEY (`id`)
 * ) ENGINE = InnoDB;
 * 
 * This file contains all the functionalities for registering 
 * a new user
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

// establish connection with database
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
            'message'       => 'Could not add user, Email or Password field can\'t be empty.'
        )
    );

    die();
}

$user->email = $data->email;
$user->password = $data->password;

// Register user
$response = $user->register();

if ($response === '403') {

    // user is already registered
    echo json_encode(
        array(
            'response code' => '403',
            'message'       => 'A user with given email is already registered.'
            )
    );

} elseif ($response === true) {
  
    // user successfully added
    echo json_encode(
        array(
            'response code' => '201',
            'message'       => 'User Added!'
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