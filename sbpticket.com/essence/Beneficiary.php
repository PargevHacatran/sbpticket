<?php
    include_once '../fetch.php';

    class Beneficiary {
        public function createBenefciary ($kpp, $inn, $name, $docsType) {
            $url = 'https://pre.tochka.com/api/v1/cyclops/v2/jsonrpc';

            $requestData = '';

            if ($docsType === 'ИП') {
                $requestData = '{
                    "id": "S8RgcKNLx0nXx84JlxKpWEht7m36efOR",
                    "jsonrpc": "2.0",
                    "method": "create_beneficiary_ip",
                    "params": {
                        "inn": "'. $_POST['inn'] .'",
                        "nominal_account_code": "40702810020000090167",
                        "nominal_account_bic": "044525104",
                        "beneficiary_data": {
                            "first_name": "'. explode(' ', $name)[1] .'",
                            "middle_name": "'. explode(' ', $name)[2] .'",
                            "last_name": "'. explode(' ', $name)[0] .'"
                        }
                    }
                }';
            } else {
                $requestData = '{
                    "id": "S8RgcKNLx0nXx84JlxKpWEht7m36efOR",
                    "jsonrpc": "2.0",
                    "method": "create_beneficiary_ip",
                    "params": {
                        "inn": "'. $inn .'",
                        "nominal_account_code": "40702810020000090167",
                        "nominal_account_bic": "044525104",
                        "beneficiary_data": {
                            "name": "'. $name .'",
                            "kpp": "' . $kpp . '"
                        }
                    }
                }';
            }

            $headers = [
                'Content-Type: application/json',
                'sign-system: bilet',
                'sign-thumbprint: b30e18d3b0bf60220d09105160391766ab7c461f',
                'sign-data: 12345',
            ];

            $response = fetch($url, $headers, $requestData);

            if ($response["connection_validation"]) {
                echo die('ERROR: ' . curl_error($response["ch"]));
            } else {
                $decodeFetch = json_decode($response["response"], true);
                $resultData = json_decode($decodeFetch, true);

                $beneficianyid = $resultData["result"]["beneficiary"]["id"];
                
                $virtualAccountReuest = '{
                    "id": "S8RgcKNLx0nXx84JlxKpWEht7m36efOR",
                    "jsonrpc": "2.0",
                    "method": "create_virtual_account",
                    "params": {
                        "beneficiary_id": "'. $beneficianyid .'"
                    }
                }';

                $createVurtualAccountresponse = fetch($url, $headers, $virtualAccountReuest);
                $decodedVirtualAccountResponse = json_decode($createVurtualAccountresponse["response"], true);
                $virtualResponse = json_decode($decodedVirtualAccountResponse, true);
                $virtualAccount = $virtualResponse["result"]["virtual_account"];

                return [
                    "virtual_account" => $virtualAccount,
                    "beneficiary_id" => $beneficianyid
                ];
            }
        }
        public function createPassword () {
            $password = '';

            for ($passwordLen = 0; $passwordLen <= 16; $passwordLen++) {
                $password = '' . $password . random_int(0, 9) . '';
            }

            return $password;
        }
        public function disactivateBanaficiary ($beneficiaryId) {
            $headers = [
                'Content-Type: application/json',
                'sign-system: bilet',
                'sign-thumbprint: b30e18d3b0bf60220d09105160391766ab7c461f',
                'sign-data: 12345',
            ];

            $disactivateRequest = '{
                "id": "S8RgcKNLx0nXx84JlxKpWEht7m36efOR",
                "jsonrpc": "2.0",
                "method": "deactivate_beneficiary",
                "params": {
                    "beneficiary_id": "'. $beneficiaryId .'"
                }
            }';

            $disactivateResponse = fetch('https://pre.tochka.com/api/v1/cyclops/v2/jsonrpc', $headers, $disactivateRequest);

            // sql запрос на удаление из таблицы
        }
    }
?>