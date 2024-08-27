<?php
    header('Access-Control-Allow-Origin: *');

    $server = 'localhost';
    $username = 'root';
    $password = 'admin';
    $dbname = 'sbpticket';

    $type = $_POST['type'];
    $carrierEmail = $_POST['carrierEmail'];

    $conn = mysqli_connect($server, $username, $password, $dbname);

    if (!$conn) {
        echo die('Faild' . mysqli_error());
    } else {
        switch ($type) {
            case 'routes':
                $data = $_POST['data'];
                
                $sql = "INSERT INTO `routes` (routes, carrierEmail) VALUES ('$data', '$carrierEmail')";
                
                $conn->query($sql);

                break;
            case 'cars':
                $data = $_POST['data'];

                $sql = "INSERT INTO `cars` (car, carrierEmail) VALUES ('$data', '$carrierEmail')";

                $conn->query($sql);

                break;
            case 'drivers':
                $name = $_POST['name'];
                $tel = $_POST['tel'];
                $tgName = $_POST['tg-name'];

                $sql = "INSERT INTO `drivers` (name, tel, tgName, carrierEmail) VALUES ('$name', '$tel', '$tgName', '$carrierEmail')";

                $conn->query($sql);

                break;
            case 'user':
                $name = $_POST['name'];
                $role = $_POST['role'];
                $email = $_POST['email'];

                $sql = "INSERT INTO `users` (name, role, email, carrierEmail) VALUES('$name', '$role', '$email', '$carrierEmail')";

                $conn->query($sql);

                break;
            default:
                echo 'No datas';
        }
    }

?>