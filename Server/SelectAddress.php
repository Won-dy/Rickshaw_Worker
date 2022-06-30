<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<?php
 header('Content-Type: application/json; charset=utf-8');

    $con = mysqli_connect("localhost", "rickshaw", "capstone1!", "rickshaw");
$response = array();
$response1 =array();

$selectManager = mysqli_query($con, "SELECT * FROM Manager");

while($row = mysqli_fetch_assoc($selectManager))
{
$response[] = $row;
}

$selectField = mysqli_query($con,"SELECT * FROM Field");

while($row = mysqli_fetch_assoc($selectField))
{
$response1[] = $row;
}


 echo json_encode($response,JSON_UNESCAPED_UNICODE);
 echo json_encode($response1,JSON_UNESCAPED_UNICODE);
 echo "bbbbbbbbbbbbb";
?>