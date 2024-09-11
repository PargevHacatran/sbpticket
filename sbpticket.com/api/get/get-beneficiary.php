<?php
    header('Access-Control-Allow-Origin: *');

    $server = 'localhost';
    $username = 'root';
    $password = 'admin';
    $dbname = 'sbpticket';

    $conn = mysqli_connect($server, $username, $password, $dbname);

    if (!$conn) {
        echo die('ERROR: ' . mysqli_error($conn));
    } else {
        $beneficiary = [];

        $sql = "SELECT * FROM `beneficiary`";
        
        $beneficiaryList = $conn->query($sql);

        if ($beneficiaryList->num_rows > 0) {
            while ($beneficiaryItem = $beneficiaryList->fetch_assoc()) {
                $beneficiaryData = [
                    "id" => $beneficiaryItem["id"],
                    "name" => $beneficiaryItem["name"],
                    "inn" => $beneficiaryItem["inn"],
                    "nominalAccountCode" => $beneficiaryItem["nominalAccountCode"],
                    "bik" => $beneficiaryItem["bik"],
                    "beneficiaryId" => $beneficiaryItem["beneficianyid"],
                ];

                array_push($beneficiary, $beneficiaryData);
            }

            echo json_encode($beneficiary);
        }
    }
?>