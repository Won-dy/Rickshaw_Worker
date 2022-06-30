<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<?php


$worker_email =$_POST["worker_email"];  

	$con = mysqli_connect("localhost", "rickshaw", "capstone1!", "rickshaw");

	$result = mysqli_query($con,"SELECT * FROM Worker WHERE worker_email = '$worker_email'");
	
echo json_encode(mysqli_fetch_assoc($result));
?>