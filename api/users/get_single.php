<?php

/**
 * This file contains functionalities to read data about 
 * a single user
 *
 * @package    Authentication_API
 * @subpackage Authentication_API/api/users
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

// intantiate user object
$user = new User($db);

// get ID from url
if (isset($_GET[ 'id' ])) {
    $user->id = $_GET[ 'id' ];
} else {
    // prepare and echo json output
    echo json_encode(
        array(
            'response code' => '400',
            'message'       => 'Could not find user, `id` field can\'t be empty.'
        )
    );

    die();
}

// get user 
if ($user->get_single()) {
    
    // create array
    $user_arr = array(
        'response code' => '200',
        'id'            => $user->id,
        'email'         => $user->email
    );

    // make JSON
    echo json_encode($user_arr);

} else {

    echo json_encode(
        array(
            'response code' => '404',
            'message'       => 'No user for given `id` and `email` found'
        )
    );

}
