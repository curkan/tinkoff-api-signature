<?php

function sortArrayByKey($array) {
    ksort($array);

    return $array;
}
// Получаем параметры из запроса
$params = [
    "TerminalKey" => "1573803282696E2C",
    "IP" => "192.168.40.74",
    "CustomerKey" => "TestCustomer3",
    "CheckType" => "NO",
];
$sortedParams = sortArrayByKey($params);
$concatenatedValues = implode('', $sortedParams);

echo $concatenatedValues . PHP_EOL;

$hash = hash('sha256', $concatenatedValues, true);
$digestValue = base64_encode($hash);
$signature = '';

$privateKeyId = openssl_get_privatekey(file_get_contents('./test-private.key'));
openssl_sign($digestValue, $signature, $privateKeyId, 'RSA-SHA256');

$signatureValue = base64_encode($signature);
$params['DigestValue'] = $digestValue;
$params['SignatureValue'] = $signatureValue;
print_r($params);

$certKey = file_get_contents('./test-public-certificate.pem');
$cert = openssl_get_publickey($certKey);
$verify = openssl_verify($digestValue, $signature, $cert, 'RSA-SHA256');

if ($verify == 1) {
    echo "Signature verified successfully";
} else {
    echo "Signature verification failed";
}
