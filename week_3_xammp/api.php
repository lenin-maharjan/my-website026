<?php
// Sample employee data
$data = [
"employee" => [
"id" => 123,
"name" => "John Doe",
"position" => "Software Engineer",
"department" => "IT"
]
];
if (isset($_GET['format']) && $_GET['format'] == 'json') {
header('Content-Type: application/json');
echo json_encode($data);
} else {
header('Content-Type: text/xml');
// Create a new XML element with root node "employee"
$xml = new SimpleXMLElement('<employee/>');
// Manually add child elements
$xml->addChild('id', $data['employee']['id']);
$xml->addChild('name', $data['employee']['name']);
$xml->addChild('position', $data['employee']['position']);
$xml->addChild('department', $data['employee']['department']);
// Output the XML
print $xml->asXML();
}
?>