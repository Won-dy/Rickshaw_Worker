<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<?php


$key = $_POST["key"];
$response = array();	
$res;
if($key == "WorkerInsert")
{
$con = mysqli_connect("localhost", "rickshaw", "capstone1!", "rickshaw");

if($con)
{
echo " connect : success <br>";
}
else
{
echo "disconnect : fail<br>";
}


	$worker_email =$_POST["worker_email"];  
	$worker_pw = $_POST["worker_pw"];
	$worker_name = $_POST["worker_name"];
	$worker_gender = $_POST["worker_gender"];
	$worker_birth = $_POST["worker_birth"];
	$worker_phonenum = $_POST["worker_phonenum"];
	$worker_certicipate = $_POST["worker_certicipate"];
	$worker_bankaccount = $_POST["worker_bankaccount"];
	$worker_bankname = $_POST["worker_bankname"];


	$statement = mysqli_prepare($con, "INSERT INTO Worker (worker_email, worker_pw, worker_name, worker_gender, worker_birth, worker_phonenum, worker_certicipate, worker_bankaccount, worker_bankname) VALUES (?,?,?,?,?,?,?,?,?)");
	mysqli_stmt_bind_param($statement, "sssssssss", $worker_email, $worker_pw, $worker_name, $worker_gender, $worker_birth, $worker_phonenum, $worker_certicipate, $worker_bankaccount, $worker_bankname );
	$res = mysqli_stmt_execute($statement);

echo "insert2workerinsert";
}
else if($key =="HopeLocalInsert")
{
//while($res==false)
//{

//}
$con = mysqli_connect("localhost", "rickshaw", "capstone1!", "rickshaw");

if($con)
{
echo " connect : success <br>";
}
else
{
echo "disconnect : fail<br>";
}


	$local_code;
	$worker_email =$_POST["worker_email"];  
	$local_sido = $_POST["local_sido"];
	$local_sigugun = $_POST["local_sigugun"];
	
	

	$selectstate = mysqli_prepare($con, "SELECT local_code  FROM Local WHERE local_sido = ? and local_sigugun = ?");
	mysqli_stmt_bind_param($selectstate, "ss", $local_sido , $local_sigugun);
	mysqli_stmt_execute($selectstate);
	
//	mysqli_store_result($selectstate);
	mysqli_stmt_bind_result($selectstate, $local_code );


	while(mysqli_stmt_fetch($selectstate))
	{
	$response["local_code"] = $local_code;
	}

	$statement = mysqli_prepare($con, "INSERT INTO HopeLocal VALUES(?,?)");
	mysqli_stmt_bind_param($statement, "ss", $worker_email, $local_code);
	if(mysqli_stmt_execute($statement))
	{
	$response["success"] = "true";
	}
	else
	{

	$response["success"] = mysqli_error($con);

	}


	echo "insert2hopelocalinsert";
	echo $response["success"];


}

?>