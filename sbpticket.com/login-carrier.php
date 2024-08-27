<?php
    header('Access-Control-Allow-Origin: *');

    $server = 'localhost';
    $username = 'root';
    $password = 'admin';
    $dbname = 'sbpticket';

    $conn = mysqli_connect($server, $username, $password, $dbname);

    if (!$conn) {
        echo die('ERROR: ' . mysqli_error());
    } else {
        $email = $_POST['email'];
        $name = $_POST['name'];
        $tel = $_POST['tel'];
        $password = $_POST['password'];
        $agentTag = $_POST['agentTag'];

        $sql = "INSERT INTO `carriers` (email, name, tel, pass, agentTag) VALUES ('$email', '$name', '$tel', '$password', '$agentTag')";

        $conn->query($sql);
    }
?>