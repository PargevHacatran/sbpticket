<?php
    header('Access-Control-Allow-Origin: *');

    $server = 'localhost';
    $username = 'root';
    $password = 'admin';
    $dbname = 'sbpticket';

    $carrierEmail = $_POST['carrierEmail'];

    $conn = mysqli_connect($server, $username, $password, $dbname);

    $sql = "INSERT INTO `drivers` (name, tel, tgName, carrierEmail) VALUES ('".$_POST['name']."', '".$_POST['tel']."', '".$_POST['tg-name']."',  '$carrierEmail')";

    $conn->query($sql);

?>