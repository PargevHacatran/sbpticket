<?php
    header("Access-Control-Allow-Origin: *");

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $server = 'localhost';
    $username = 'root';
    $password = 'admin';
    $dbname = 'sbpticket';

    $conn = mysqli_connect($server, $username, $password, $dbname);

    $login = $_POST["login"];
    $password = $_POST["password"];

    if (!$conn) {
        echo die("ERROR: " . mysqli_error($conn));
    } else {
        $sql = "SELECT * FROM `beneficiary` WHERE name='$login' AND password='$password'";

        $getBeneficiary = $conn->query($sql);

        if ($getBeneficiary->num_rows > 0) {
            while ($beneficiary = $getBeneficiary->fetch_assoc()) {
                echo json_encode($beneficiary);
            }
        } else {
            echo "Error Logining: Invalid Login or Password";
        }
    }
?>