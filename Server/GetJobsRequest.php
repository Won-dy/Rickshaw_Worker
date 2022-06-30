<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<?php
 header('Content-Type: application/json; charset=utf-8');
$con = mysqli_connect("localhost", "rickshaw", "capstone1!", "rickshaw");
$response = array();

$i=0;
$data1 =  mysqli_query($con, "SELECT job_name FROM Job") or die(mysqli_error($con));
while($row = mysqli_fetch_array($data1)){  
	array_push($response, array('jobname'=>$row[0]));
	}



echo json_encode(array('response'=>$response),JSON_UNESCAPED_UNICODE);
?>