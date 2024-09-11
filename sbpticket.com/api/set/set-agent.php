<?php
    include_once '../../essence/Beneficiary.php';
    include_once '../../fetch.php';

    header('Access-Control-Allow-Origin: *');

    $server = 'localhost';
    $username = 'root';
    $password = 'admin';
    $dbname = 'sbpticket';

    $conn = mysqli_connect($server, $username, $password, $dbname);

    if (!$conn) {
        echo die('ERROR:' . mysqli_connect($conn));
    } else {
        $bik = $_POST['bik'];
        $kpp = $_POST['kpp'];

        $beneficiary = new Beneficiary();

        $beneficiaryData = $beneficiary->createBenefciary($kpp, $_POST["inn"], $_POST["name"], $_POST["docsType"]);
        $password = $beneficiary->createPassword();

        $message = file_get_contents('../../public/email/index.html');

        $jsonMessage = json_encode(str_replace('pass', $password, $message));

        $agentSql = "INSERT INTO `agents` (beneficianyid, name, region, docsType, inn, nominalAccountCode, bik, kpp, orgn, tag, virtualAccount, password, email) VALUES ('".$beneficiaryData['beneficiary_id']."', '".$_POST['name']."', '".$_POST['region']."', '".$_POST['docsType']."', '".$_POST['inn']."', '".$_POST['nominalAccountCode']."', $bik, $kpp, '".$_POST['orgn']."', '".$_POST['tgName']."', '".$beneficiaryData['virtual_account']."', '$password', '".$beneficiaryData['email']."')";

        $conn->query($agentSql);

        $beneficiarySql = "INSERT INTO `beneficiary` (beneficianyid, name, docsType, inn, nominalAccountCode, bik, kpp, orgn, tag, virtualAccount, password, email) VALUES ('".$beneficiaryData['beneficiary_id']."', '".$_POST['name']."', '".$_POST['docsType']."', '".$_POST['inn']."', '".$_POST['nominalAccountCode']."', $bik, $kpp, '".$_POST['orgn']."', '".$_POST['tgName']."', '".$beneficiaryData['virtual_account']."', '$password', '".$beneficiaryData['email'].")";

        $conn->query($beneficiarySql);
    }

?>