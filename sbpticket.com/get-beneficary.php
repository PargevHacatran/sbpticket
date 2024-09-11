<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    

    function fetch ($url, $headers, $datas) {
        $privateKey = file_get_contents('openssl/rsaprivkey2.pem');        
        $privateKeyResource = openssl_pkey_get_private($privateKey);

        if ($privateKeyResource === false) {
            throw new Exception('Invalid private key provided.');
        }

        openssl_sign($datas, $signature, $privateKeyResource, OPENSSL_ALGO_SHA256);        

        $base64Signature = base64_encode($signature);
        $signData = preg_replace('%^\s+|\s+$%u', '', $base64Signature);

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS => $datas,
            CURLOPT_HTTPHEADER => $headers
        ]);

        $response = curl_exec($ch);

        curl_close($ch);

        return [
            "connection_validation" => curl_errno($ch),
            "ch" => $ch,
            "response" => json_encode($response)
        ];
    }


    $url = 'https://pre.tochka.com/api/v1/cyclops/v2/jsonrpc';

    $data = [
        "id" => "S8RgcKNLx0nXx84JlxKpWEht7m36efOR",
        "jsonrpc" => "2.0",
        "method" => "list_beneficiary",
        "params" => [
            "filters" => [               
                "nominal_account_code" => "40702810020000090167",
                "nominal_account_bic" => "044525104",
            ]
        ]
    ];

    $headers = [
        'Content-Type: application/json',
        'sign-system: bilet',
        'sign-thumbprint: b30e18d3b0bf60220d09105160391766ab7c461f',
        'sign-data: 12345',
    ];

    $response = fetch($url, $headers, json_encode($data));

    if ($response["connection_validation"]) {
        echo die('ERROR: ' . curl_error($response["ch"]));
    } else {
        echo json_decode($response["response"], true);
    }
?>