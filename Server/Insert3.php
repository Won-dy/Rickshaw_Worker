<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<?php


$key = $_POST["key"];
//$response = array();	

if($key == "HopeJobInsert")
{
	$con = mysqli_connect("localhost", "rickshaw", "capstone1!", "rickshaw");


	$worker_email =$_POST["worker_email"];  
	$job_codest = $_POST["job_code"];
	$job_codeint = (int)$job_codest;
	$hj_career = $_POST["hj_career"];
	
//	$response["job_codest"] = $job_codest;
//	$response["hj_career"] = $hj_career;

	
	$statement = mysqli_prepare($con, "INSERT INTO HopeJob  VALUES (?,?,?)");
	mysqli_stmt_bind_param($statement, "sis", $worker_email, $job_codeint, $hj_career);
	$result = mysqli_stmt_execute($statement);
	echo "insert3";
	echo mysqli_error($con);
}
?>