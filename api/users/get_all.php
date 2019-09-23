<?php

/**
 * This file contains functionalities 
 * to retrieve all the user information
 * from the database
 * 
 * @package    Authentication_API
 * @subpackage Authentication_API/models
 */

// headers
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');    

// includes
require_once '../../config/database.php';
require_once '../../models/user.php';

// instantiate database & connect
$database = new Database();

// connect is user defined function
$db = $database -> connect();

// intantiate blog user object
$user = new User($db);

$result = $user->get_all();

// get row count
$num = $result->rowCount();

// check if there are any users
if ($num > 0) {

    // user array
    $users_arr = array();
    $users_arr['response code'] = '';
    $users_arr['data'] = array();
    
    // associative array
    while ($row = $result -> fetch(PDO::FETCH_ASSOC)) {

        extract($row);

        $user_item = array(
            'id'       => $id,
            'email'    => $email,
            'password' => $password
        );

        // push to 'data'
        array_push($users_arr['data'], $user_item);
    }
    $users_arr['response code'] = '200';

    // turn to JSON & output
    echo json_encode($users_arr);

} else {

    // no users
    echo json_encode(
        array(
            'response code' => '404',
            'message'       => 'No users Found'
        )
    );
}