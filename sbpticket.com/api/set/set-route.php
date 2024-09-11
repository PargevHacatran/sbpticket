<?php
    header('Access-Control-Allow-Origin: *');

    $server = 'localhost';
    $username = 'root';
    $password = 'admin';
    $dbname = 'sbpticket';

    $carrierEmail = $_POST['carrierEmail'];

    $conn = mysqli_connect($server, $username, $password, $dbname);

    $sql = "INSERT INTO `routes` (routes, carrierEmail) VALUES ('".$_POST['data']."', '$carrierEmail')";

    $conn->query($sql);

?>