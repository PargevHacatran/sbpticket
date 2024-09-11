<?php
    function fetch ($url, $headers, $datas) {
        $privateKey = file_get_contents(__DIR__ . '/openssl/rsaprivkey2.pem');        
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
            CURLOPT_HTTPHEADER => [
                "sign-data: 12345",
                ...$headers,
            ]
        ]);
    
        $response = curl_exec($ch);
    
        curl_close($ch);
    
        return [
            "connection_validation" => curl_errno($ch),
            "ch" => $ch,
            "response" => json_encode($response)
        ];
    }
?>