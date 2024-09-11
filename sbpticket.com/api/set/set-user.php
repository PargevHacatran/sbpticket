<?php
    header('Access-Control-Allow-Origin: *');

    $server = 'localhost';
    $username = 'root';
    $password = 'admin';
    $dbname = 'sbpticket';

    $carrierEmail = $_POST['carrierEmail'];

    $conn = mysqli_connect($server, $username, $password, $dbname);

    $sql = "INSERT INTO `users` (name, role, email, carrierEmail) VALUES ('".$_POST['name']."', '".$_POST['role']."', '".$_POST['email']."', '".$carrierEmail."')";

    $conn->query($sql);

?>