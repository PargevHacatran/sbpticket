<?php
    header('Access-Control-Allow-Origin: *');

    $server = 'localhost';
    $username = 'root';
    $password = 'admin';
    $dbname = 'sbpticket';

    $conn = mysqli_connect($server, $username, $password, $dbname);

    $key = $_POST['key'];
    $type = $_POST['type'];

    if (!$conn) {
        echo die('ERROR: ' . mysqli_error());
    } else {
        switch ($type) {
            case 'drivers':
                $name = $_POST['name'];
                $tel = $_POST['tel'];
                $tgName = $_POST['tgName'];

                $sql = "UPDATE `drivers` SET name='$name', tel='$tel', tgName='$tgName' WHERE id=$key";

                $conn->query($sql);
                break;
            case 'routes':
                $routes = $_POST['routes'];

                $sql = "UPDATE `routes` SET routes='$routes' WHERE id=$key";

                $conn->query($sql);
                break;
            case 'cars':
                $car = $_POST['car'];

                $sql = "UPDATE `cars` SET car='$car' WHERE id=$key";

                $conn->query($sql);
                break;
            case 'users':
                $name = $_POST['name'];
                $role = $_POST['role'];
                $email = $_POST['email'];
                
                $sql = "UPDATE `users` SET name='$name', role='$role', email='$email' WHERE id=$key";

                $conn->query($sql);
                break;
            case 'agents':
                $name = $_POST['name'];
                $tgName = $_POST['tgName'];
                $region = $_POST['region'];
                
                $sql = "UPDATE `agents` SET name='$name', tag='$tgName', region='$region' WHERE id=$key";

                $conn->query($sql);
                break;
            default:
                echo 'No datas';
        }
    }

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
?>