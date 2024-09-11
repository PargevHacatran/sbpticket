<?php
    header('Access-Control-Allow-Origin: *');

    include_once '../../fetch.php';

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $server = 'localhost';
    $username = 'root';
    $password = 'admin';
    $dbname = 'sbpticket';

    $conn = mysqli_connect($server, $username, $password, $dbname);
    if (!$conn) {
        echo die('ERROR:' . mysqli_error($conn));
    } else {
        $virtualAccounts = [];

        $headers = [
            'Content-Type: application/json',
            'sign-system: bilet',
            'sign-thumbprint: b30e18d3b0bf60220d09105160391766ab7c461f'
        ];

        $url = 'https://pre.tochka.com/api/v1/cyclops/v2/jsonrpc';

        $sql = "SELECT * FROM `beneficiary`";
            
        $beneficiaryList = $conn->query($sql);

        if ($beneficiaryList->num_rows > 0) {
            while ($beneficiaryRow = $beneficiaryList->fetch_assoc()) {
                $requestData = '{
                    "id": "S8RgcKNLx0nXx84JlxKpWEht7m36efOR",
                    "jsonrpc": "2.0",
                    "method": "get_virtual_account",
                    "params": {
                        "virtual_account": "'.$beneficiaryRow["virtualAccount"].'"
                    }
                }';

                $response = fetch($url ,$headers, $requestData);
                $decodedResult = json_decode(json_decode($response["response"], true), true);

                echo json_encode($response); 

                $virtualAccountData = [
                    "name" => $beneficiaryRow["name"],
                    "inn" => $beneficiaryRow["inn"],
                    "virtualAccountCode" => $beneficiaryRow["virtualAccount"],
                    "balance" => $decodedResult["result"]["virtual_account"]["cash"]
                ];

                array_push($virtualAccounts, $virtualAccountData);
            }
        }

        echo json_encode($virtualAccounts);
    }
?>