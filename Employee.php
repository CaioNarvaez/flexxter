<?php


class Employee {

    /**
     * Employee's unique id
     * @var int $id
     */
    public $id;

    /**
     * Employee's firstname
     * @var string $firstname
     */
    public $firstname;


    /**
     * Employee's surname
     * @var string $surname
     */
    public $surname;

    /**
     * Hashed als salted password
    * @var string $password
    */
    public $password;


    // Methods

    public function set_id($id) {
        $this->id = $id;
    }
    public function get_id() {
        return $this->id;
    }

    public function set_firstname($firstname) {
        $this->firstname = $firstname;
    }
    public function get_firstname() {
        return $this->firstname;
    }

    public function set_surname($surname) {
        $this->surname = $surname;
    }
    public function get_surname() {
        return $this->surname;
    }

    public function set_password($password) {
        $this->password = $password;
    }
    public function get_password() {
        return $this->password;
    }

}


?>