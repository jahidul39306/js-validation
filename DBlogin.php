<?php
    require "DBconnect.php";
    function searchUser($username, $password)
    {
        $conn = connect();
        $sql = $conn->prepare("SELECT * FROM USERS WHERE username = ? and password = ?");
        $sql->bind_param("ss", $username, $password);
        $sql->execute();
        $res = $sql->get_result();
        return $res->num_rows === 1;
    }
?>