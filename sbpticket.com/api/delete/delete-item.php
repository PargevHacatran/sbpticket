<?php
    header('Access-Control-Allow-Origin: *');

    $server = 'localhost';
    $username = 'root';
    $password = 'admin';
    $dbname = 'sbpticket';

    $type = $_POST['type'];
    $key = $_POST['key'];

    $conn = mysqli_connect($server, $username, $password, $dbname);

    if (!$conn) {
        echo die('ERROR:' . mysqli_error($conn));
    } else {
        $sql = "DELETE FROM `$type` WHERE id=" . $key . "";

        $conn->query($sql);
    }
?>