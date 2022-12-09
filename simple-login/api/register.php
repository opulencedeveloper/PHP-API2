<?php

header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');
header('Access-Control-Allow-Method: POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, Accept');

include_once '../models/Users.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($user->validate_params($_GET['signUpType'] === 'signUpWithEmail')) {

        if ($user->validate_params($_POST['email'])) {
            $user->email = $_POST['email'];
        } else {
            echo json_encode(array('success' => 0, 'message' => 'Email is required!'));
            die();
        }

        if ($user->validate_params($_POST['password'])) {
            $user->password = $_POST['password'];
        } else {
            echo json_encode(array('success' => 0, 'message' => 'Password is required!'));
            die();
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(array('success' => 0, 'error' => 'INVALID_EMAIL'));
            die();
          }

        if ($user->check_unique_email()) {
            if ($id = $user->register_seller()) {
                echo json_encode(array('success' => 1, 'message' => 'User Registered'));
            } else {
                http_response_code(500);
                echo json_encode(array('success' => 0, 'message' => 'Internal Server Error'));
            }
        } else {
            http_response_code(401);
            echo json_encode(array('success' => 0, 'error' => 'EMAIL_EXISTS'));
        }


    } elseif ($user->validate_params($_GET['signUpType'] === 'signUpWithNo')) {
        if ($user->validate_params($_POST['tel_no'])) {
            $user->tel_no = $_POST['tel_no'];
        } else {
            echo json_encode(array('success' => 0, 'message' => 'Phone number is required!'));
            die();
        }

        if ($user->validate_params($_POST['password'])) {
            $user->password = $_POST['password'];
        } else {
            echo json_encode(array('success' => 0, 'message' => 'Password is required!'));
            die();
        }

        if ($user->check_unique_no()) {
            if ($id = $user->register_seller()) {
                echo json_encode(array('success' => 1, 'message' => 'User Registered'));
            } else {
                http_response_code(500);
                echo json_encode(array('success' => 0, 'message' => 'Internal Server Error'));
            }
        } else {
            http_response_code(401);
            echo json_encode(array('success' => 0, 'error' => 'PHONE_NO_EXIST'));
        }
    }
} else {
    die(header('HTTP/1.1 405 Request Method Not Allowed'));
}
