<?php
    include_once './fetch.php';

    header('Access-Control-Allow-Origin: *');

    $server = 'localhost';
    $username = 'root';
    $password = 'admin';
    $dbname = 'sbpticket';

    $carrierEmail = $_POST['carrierEmail'];

    $conn = mysqli_connect($server, $username, $password, $dbname);

    $sql = "INSERT INTO `cars` (car, carrierEmail) VALUES ('".$_POST['data']."', '$carrierEmail')";

    $conn->query($sql);

    $requestData = '{
        "Data": {
            "amount": 1,
            "currency": "RUB",
            "paymentPurpose": "LB0001282979",
            "qrcType": "01",
            "imageParams": {
                "width": 500,
                "height": 500,
                "mediaType": "image/png"
            },
            "sourceName": "sbpticket",
            "redirectUrl": "https://tranzit-bilet.ru/bank/redirect"
        }
    }';
            
    $fetchResult = fetch('https://enter.tochka.com/uapi/sbp/v1.0/qr-code/merchant/MB0000965668/40702810820000120774/044525104', ['Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpc3MiOiJpODZCVlVOTFhscTF2Y2M0bGxSTldwdnlIU25nRHJ5SyJ9.SyH1PP46-NRZRXfCQ9v9aiLzffpnbmEa_KG19kdzcF779WpAYANkHy3j8m5EoA87NPu0P-_Zf8vsugxJGrZWwFb3ExK6xa9kQUiszczJEVG_Zi2E2dUfu89nvrlpOCRtDijbvvet_bPAupQBjlyhjRHNwvg9Z2giIlwSibaOO8_0zQpX0TtmqNptfoR7HxoDeIxbvxCxl09wvsILCQpzYkv1b5azQM7QZNLMMsLmPVXiuj1fSuN6EArWMeFl5oFwtjDTuJJpzuFP7JkI5jCyVEm--S2Kb_LwbC93H5B-Sysi5TmUHvatkkxwvR3zeSYPyQ_yP_lqgf3tdL-YM96PYlvQFmG9OcFnMIlE1xrqUbVhQD5fA8ZF1o58eoPkn3NZ2jS_KMHraH56tJvrqBRx_XpdR8WjZr2GP0fRneOTJLagbPtzGmfzBI85dMlljcunj_p6LVTwAYENQCF6K9yTGV1sn97ODOeDO0SPXSOdyw8ab618IrCfOk7JAjTRNgpU','Content-Type: application/json',], $requestData);

    $response = json_decode($fetchResult['response'], true);
            
    if ($fetchResult['connection_validation']) {
        echo die('ERROR');
    } else {
        $data = json_decode($response, true);

        $qrcId = $data['Data']['qrcId'];

        $qrSQL = "INSERT INTO `qrs` (qrId, carrierEmail) VALUES ('$qrcId', '$carrierEmail')";

        $conn->query($qrSQL);
    }

?>