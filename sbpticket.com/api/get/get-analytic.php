<?php
    header('Access-Control-Allow-Origin: *');

    $server = 'localhost';
    $username = 'root';
    $password = 'admin';
    $dbname = 'sbpticket';

    $conn = mysqli_connect($server, $username, $password, $dbname);

    if (!$conn) {
        echo die('ERROR: ' . mysql_error()); 
    } else {
        $driversSQL = "SELECT * FROM `drivers`";
        $routesSQL = "SELECT * FROM `routes`";
        $carsSQL = "SELECT * FROM `cars`";

        $carsLength = $conn->query($carsSQL)->num_rows;
        $routesLength = $conn->query($routesSQL)->num_rows;
        $driversLength = $conn->query($driversSQL)->num_rows;
        
        $analytic = (object) [
                "Количество Водителей" => $driversLength,
                "Количество Маршрутов" => $routesLength,
                "Количество Транспортных Средств" => $carsLength
        ];

        echo json_encode($analytic);
    }
?>