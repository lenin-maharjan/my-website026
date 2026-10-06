<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$policy = $_POST['policy'];
$url = "http://odrlapi.appspot.com/service/evaluate";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
curl_setopt($ch, CURLOPT_POSTFIELDS, $policy);
$response = curl_exec($ch);
if (curl_errno($ch)) {
echo 'Error:' . curl_error($ch);
} else {
echo htmlentities($response);
}
curl_close($ch);
}
?>