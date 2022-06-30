<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<?php

header('Content-Type: application/json; charset=utf-8');
$con = mysqli_connect("localhost", "rickshaw", "capstone1!", "rickshaw");

	
        $token = $_POST["token"];
		$id = $_POST["worker_email"];
		//데이터베이스에 접속해서 토큰을 저장
		if($id == "0"){
			mysqli_query($con, "DELETE FROM AccountPermission WHERE Token ='$token'");
		}
		else{
			$data3 = mysqli_query($con, "SELECT Token FROM AccountPermission WHERE Token ='$token'");
        	while($row =mysqli_fetch_array($data3)) {
                mysqli_query($con, "DELETE FROM AccountPermission WHERE Token ='$token'");
		    }
		
          
		$query = "INSERT INTO AccountPermission(id,Token) Values ('$id','$token')  ON DUPLICATE KEY UPDATE Token = '$token'";
		mysqli_query($con, $query);
		}

		mysqli_close($con);
	
?>