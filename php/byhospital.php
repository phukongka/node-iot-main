<?php

$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "http://localhost/covid-php-api/api/api.php",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => [
    "Service-Name: byhospital",
    "Service-Parameter: 14591",
    "User-Pass: 24c9e15e52afc47c225b757e7bee1f9d",
    "User-Request: user1"
  ],
]);

$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$err = curl_error($curl);
curl_close($curl);


if ($err) {
  echo "cURL Error #:" . $err;
} else {
  // ข้อมูลที่ได้จาก API
  // echo $response;

  // แสดงผล JSON ที่ได้จาก API
  $json = json_decode($response, true);
  echo "<pre>" . json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "</pre>";
}