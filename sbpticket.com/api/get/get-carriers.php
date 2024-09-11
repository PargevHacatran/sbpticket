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
        $sql = "SELECT * FROM `carriers`";

        $carrierstList = $conn->query($sql);

        $carriers = [];

        if ($carrierstList->num_rows > 0) {
            while ($carrierRow = $carrierstList->fetch_assoc()) {
                array_push($carriers, $carrierRow);
            }
        }

        echo json_encode($carriers);
    }
?>