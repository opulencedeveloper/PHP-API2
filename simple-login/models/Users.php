<?php

$ds = DIRECTORY_SEPARATOR;
$base_dir = realpath(dirname(__FILE__). $ds . '..') . $ds;

require_once("{$base_dir}includes{$ds}Database.php");
require_once("{$base_dir}includes{$ds}Bcrypt.php");

class User{
    private $table = 'users';

    public $id;
    public $email;
    public $tel_no;
    public $password;

    public function __construct() {}

    public function validate_params($value)
    {
        return (!empty($value));
    }

    public function check_unique_email() {
        global $database;

        $this->email = trim(htmlspecialchars(strip_tags($this->email)));

        $sql = "SELECT id FROM $this->table WHERE email = '" .$database->escape_value($this->email). "'";

        $result = $database->query($sql);
        $user_id = $database->fetch_row($result);

        return empty($user_id);
    }

    public function check_unique_no() {
        global $database;

        $this->tel_no = trim(htmlspecialchars(strip_tags($this->tel_no)));

        $sql = "SELECT id FROM $this->table WHERE tel_no = '" .$database->escape_value($this->tel_no). "'";

        $result = $database->query($sql);
        $user_id = $database->fetch_row($result);

        return empty($user_id);
    }

    public function register_seller() {
        global $database;

        $this->email = trim(htmlspecialchars(strip_tags($this->email)));
        $this->tel_no = trim(htmlspecialchars(strip_tags($this->tel_no)));
        $this->password = trim(htmlspecialchars(strip_tags(Bcrypt::hashPassword($this->password))));
        
        $sql = "INSERT INTO $this->table (email, tel_no, password) VALUES(
            '" .$database->escape_value($this->email). "',
            '" .$database->escape_value($this->tel_no). "',
            '" .$database->escape_value($this->password). "'
        )";

        $seller_saved = $database->query($sql);
        if($seller_saved) return true;
        else false;
    }

    public function login_with_email() {
        global $database;

        $this->email = trim(htmlspecialchars(strip_tags($this->email)));
        $this->password = trim(htmlspecialchars(strip_tags($this->password)));

        $sql = "SELECT * FROM $this->table WHERE email = '" .$database->escape_value($this->email). "'";

        $result = $database->query($sql);
        $user = $database->fetch_row($result);

        if(empty($user)) {
            return "USER_DOES_NOT_EXIST";
        } else {
            if (Bcrypt::checkPassword($this->password, $user['password'])) {
                unset($user['password']);                                                             //unset prevents the api from returning the password, here it removes password from the values in the seller array
                return $user;
            } else {
                return "PASSWORD_DOES_NOT_MATCH";
            }
        }
    }

    public function login_with_no() {
        global $database;

        $this->tel_no = trim(htmlspecialchars(strip_tags($this->tel_no)));
        $this->password = trim(htmlspecialchars(strip_tags($this->password)));

        $sql = "SELECT * FROM $this->table WHERE tel_no = '" .$database->escape_value($this->tel_no). "'";

        $result = $database->query($sql);
        $user = $database->fetch_row($result);

        if(empty($user)) {
            return "USER_DOES_NOT_EXIST";
        } else {
            if (Bcrypt::checkPassword($this->password, $user['password'])) {
                unset($user['password']);                                                             //unset prevents the api from returning the password, here it removes password from the values in the seller array
                return $user;
            } else {
                return "PASSWORD_DOES_NOT_MATCH";
            }
        }
    }


}

$user = new User();