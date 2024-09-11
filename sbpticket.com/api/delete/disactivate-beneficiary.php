<?php
    include_once '../../fetch.php';
    include_once '../../essence/Beneficiary.php';

    header("Access-Control-Allow-Origin: *");

    $server = 'localhost';
    $username = 'root';
    $password = 'admin';
    $dbname = 'sbpticket';

    $conn = mysqli_connect($server, $username, $password, $dbname);

    $beneficiaryId = $_POST["beneficiaryId"];

    $beneficiary = new Beneficiary();

    $beneficiary->disactivateBanaficiary($beneficiaryId);

    if (!$conn) {
        echo die("ERROR: " . mysqli_error($conn));
    } else {
        $sql = "SELECT * FROM `agents` WHERE beneficianyid='$beneficiaryId'";

        $getBeneficiary = $conn->query($sql);

        if ($getBeneficiary->num_rows > 0) {
            $sql = "DELETE FROM `agents` WHERE beneficianyid='$beneficiaryId'";

            $conn->query($sql);
        } else {
            $sql = "DELETE FROM `carriers` WHERE beneficianyid='$beneficiaryId'";
            
            $conn->query($sql);
        }

        $sql = "DELETE FROM `beneficiary` WHERE beneficianyid='$beneficiaryId'";

        $conn->query($sql);
    }

?>