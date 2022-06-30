<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<?php
 header('Content-Type: application/json; charset=utf-8');
    $con = mysqli_connect("localhost", "rickshaw", "capstone1!", "rickshaw");

$response = array();
$response1 = array();
$response2 = array();
$response3 = array();

$field_code = $_POST["field_code"];

$result = mysqli_query($con, "SELECT * FROM Field WHERE Field.field_code = '$field_code'");

$row = mysqli_fetch_assoc($result);
  
  $response[] = $row;
  $jp_num = $row["jp_num"];



$result = mysqli_query($con, "SELECT * FROM JobPosting WHERE JobPosting.jp_num = '$jp_num'");

$row = mysqli_fetch_assoc($result);  

$response1[] = $row;
$job_code = $row["job_code"];
$business_reg_num = $row["business_reg_num"];

$result = mysqli_query($con, "SELECT * FROM Job WHERE Job.job_code = '$job_code'");

$row = mysqli_fetch_assoc($result);

$response2[] = $row;


$result = mysqli_query($con, "SELECT * FROM Manager WHERE Manager.business_reg_num = '$business_reg_num'");

$row = mysqli_fetch_assoc($result);

$response3[] = $row;


echo json_encode($response,JSON_UNESCAPED_UNICODE);
echo json_encode($response1,JSON_UNESCAPED_UNICODE);
echo json_encode($response2,JSON_UNESCAPED_UNICODE);
echo json_encode($response3,JSON_UNESCAPED_UNICODE);
?>