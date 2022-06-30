<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<?php
 header('Content-Type: application/json; charset=utf-8');
    $con = mysqli_connect("localhost", "rickshaw", "capstone1!", "rickshaw");

$response = array();

$manager_office_name = $_POST["manager_office_name"];

$result = mysqli_query($con, "SELECT * FROM Manager WHERE manager_office_name = '$manager_office_name'");

$row = mysqli_fetch_assoc($result);
  
  $response[] = $row;

echo json_encode($response,JSON_UNESCAPED_UNICODE);
?>