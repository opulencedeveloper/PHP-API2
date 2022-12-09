<?php

header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');
header('Access-Control-Allow-Method: POST');
header('Access-Control-Allow-Headers: Origin, Content-type, Accept');

include_once '../models/Users.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($user->validate_params($_GET['signInType'] === 'signInWithEmail')) {
    if($user->validate_params($_POST['email'])) {
        $user->email = $_POST['email'];
    } else {
        echo json_encode(array('success' => 0, 'error' => 'Email is required'));
        die();
    }

    if($user->validate_params($_POST['password'])) {
        $user->password = $_POST['password'];
    } else {
        echo json_encode(array('success' => 0, 'error' => 'Password is required'));
        die();
    }

    $s = $user->login_with_email();
    if(gettype($s) === 'array') {
        http_response_code(200);
        echo json_encode(array('success' => 1, 'message' => 'Login Successful',  'user' => $s));
    } else {
        http_response_code(402);
        echo json_encode(array('success' => 0, 'error' => $s));
    }
}

if ($user->validate_params($_GET['signInType'] === 'signInWithNo')) {
    if($user->validate_params($_POST['tel_no'])) {
        $user->tel_no = $_POST['tel_no'];
    } else {
        echo json_encode(array('success' => 0, 'error' => 'Phone Number is required'));
        die();
    }

    if($user->validate_params($_POST['password'])) {
        $user->password = $_POST['password'];
    } else {
        echo json_encode(array('success' => 0, 'error' => 'Password is required'));
        die();
    }

    $s = $user->login_with_no();
    if(gettype($s) === 'array') {
        http_response_code(200);
        echo json_encode(array('success' => 1, 'message' => 'Login Successful',  'user' => $s));
    } else {
        http_response_code(402);
        echo json_encode(array('success' => 0, 'error' => $s));
    }
}

} else {
    die(header('HTTP/1.1 405 Request Method Not Allowed'));
}