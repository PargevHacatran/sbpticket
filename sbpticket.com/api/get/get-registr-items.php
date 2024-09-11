<?php
    header('Access-Control-Allow-Origin: *');

    $server = 'localhost';
    $username = 'root';
    $password = 'admin';
    $dbname = 'sbpticket';

    $conn = mysqli_connect($server, $username, $password, $dbname);

    if (!$conn) {
        echo die('Faild: ' . mysqli_error());
    } else {
        $driversSQL = "SELECT * FROM `drivers`";

        $driversList = $conn->query($driversSQL);

        $drivers = [];

        if ($driversList->num_rows > 0) {
            while ($driverRow = $driversList->fetch_assoc()) {
                array_push($drivers, $driverRow);
            }
        }

        $routesSQL = "SELECT * FROM `routes`";

        $routesList = $conn->query($routesSQL);

        $routes = [];

        if ($routesList->num_rows > 0) {
            while ($routesRow = $routesList->fetch_assoc()) {
                array_push($routes, $routesRow);
            }
        }

        $carsSQL = "SELECT * FROM `cars`";

        $carsList = $conn->query($carsSQL);

        $cars = [];

        if ($carsList->num_rows > 0) {
            while ($carsRow = $carsList->fetch_assoc()) {
                array_push($cars, $carsRow);
            }
        }

        $usersSQL = "SELECT * FROM `users`";

        $usersList = $conn->query($usersSQL);

        $users = [];

        if ($usersList->num_rows > 0) {
            while ($usersRow = $usersList->fetch_assoc()) {
                array_push($users, $usersRow);
            }
        }

        $agentsSQL = "SELECT * FROM `agents`";

        $agentsList = $conn->query($agentsSQL);

        $agents = [];

        if ($agentsList->num_rows > 0) {
            while ($agentsRow = $agentsList->fetch_assoc()) {
                array_push($agents, $agentsRow);
            }
        }

        echo json_encode([$drivers, $routes, $cars, $users, $agents]);
    }

?>