<?php
    require "DBconnect.php";

    function register($fname, $lname, $gender, $dob, $religion, $presAddress, $permAddress, $phone, $email, $website, $username, $password)
    {
        $dob = date("Y-m-d", strtotime($dob));
        $conn = connect();
        $sql = $conn->prepare("INSERT INTO USERS (fname, lname, gender, dob, religion,
                            present_Address, permanent_Address, phone, email, website,
                                username, password) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $sql->bind_param("ssssssssssss", $fname, $lname, $gender, $dob, $religion,
        $presAddress, $permAddress, $phone, $email, $website, $username, $password);
        return $sql->execute();
    
    }

    function searchUsername($username)
    {
        $conn = connect();
        $sql = $conn->prepare("SELECT * FROM USERS WHERE username = ?");
        $sql->bind_param("s", $username);
        $sql->execute();
        $res = $sql->get_result();
        return $res->num_rows;
    }
?>