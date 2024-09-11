<?php
    include_once '../../essence/Beneficiary.php';
    include_once '../../fetch.php';

    header('Access-Control-Allow-Origin: *');

    $server = 'localhost';
    $username = 'root';
    $password = 'admin';
    $dbname = 'sbpticket';

    $carrierEmail = $_POST['carrierEmail'];

    $conn = mysqli_connect($server, $username, $password, $dbname);

    if (!$conn) {

    } else {
        $bik = $_POST['bik'];
        $kpp = $_POST['kpp'];

        $beneficiary = new Beneficiary();

        $beneficiaryData = $beneficiary->createBenefciary($kpp, $_POST["inn"], $_POST["name"], $_POST["docsType"]);
        $password = $beneficiary->createPassword();

        $message = file_get_contents('./public/email/index.html');

        $jsonMessage = json_encode(str_replace('pass', $password, $message));

        $agentSql = "INSERT INTO `carriers` (beneficianyid, email, tel, name, docsType, inn, nominalAccountCode, bik, kpp, orgn, virtualAccount, password, agentTag) VALUES ('".$beneficiaryData['beneficiary_id']."', '".$_POST['email']."', '".$_POST['tel']."', '".$_POST['name']."', '".$_POST['docsType']."', '".$_POST['inn']."', '".$_POST['nominalAccountCode']."', $bik, $kpp, '".$_POST['orgn']."', '".$beneficiaryData['virtual_account']."', '$password', '".$_POST['agentTag']."')";

        $conn->query($agentSql);

        $beneficiarySql = "INSERT INTO `beneficiary` (beneficianyid, name, docsType, inn, nominalAccountCode, bik, kpp, orgn, tag, virtualAccount, password, email) VALUES ('".$beneficiaryData['beneficiary_id']."', '".$_POST['name']."', '".$_POST['docsType']."', '".$_POST['inn']."', '".$_POST['nominalAccountCode']."', $bik, $kpp, '".$_POST['orgn']."', '".$_POST['tgName']."', '".$beneficiaryData['virtual_account']."', '$password', '".$_POST['email']."')";

        $conn->query($beneficiarySql);
    }
?>