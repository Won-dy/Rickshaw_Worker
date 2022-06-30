<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<?php
    ini_set("memory_limit",-1);
    header('Content-Type: application/json; charset=utf-8');
    // 서버와 DB에 접속
    // $con = mysqli_connect(host, username(id), passwd, DB명);
    $con = mysqli_connect("localhost", "rickshaw", "capstone1!", "rickshaw");

    if($con)
    {
    echo " connect : success <br>";
    }
    else
    {
    echo "disconnect : fail<br>";
    }
    $response = array();

    //$worker_email = '3';
    //$key1 = '2';
    $worker_email = $_POST['worker_email'];
    $key1 = $_POST['k'];
    //$business_reg_num=[];
    $field_code=[];
    $field_name=[];
    $office_name=[];
    $worker_name=[];
    $worker_name1=[];
	  $worker_name11=[];
    $worker_email1=[];
    $business_reg_num=[];
    $a=0;
    $b=0;
    $e=0;

    if($key1 == '1'){
		$k=0;
        $getnickname = mysqli_query($con, "SELECT worker_email FROM OfficeReview WHERE business_reg_num='$worker_email'");
        while($row1 =mysqli_fetch_array($getnickname)) {
            $worker_name[$k] = $row1[0];
			$k++;
        }
for($i=0; $i<$k; $i++){
	$getnickname11 = mysqli_query($con, "SELECT worker_name FROM Worker WHERE worker_email='$worker_name[$i]'");
        while($row2 =mysqli_fetch_array($getnickname11)) {
            $worker_name11[] = $row2[0];
        }
}

        }
    else if($key1=='2'){
        $getnickname1 = mysqli_query($con, "SELECT FieldReview.worker_email, Field.field_code FROM Field JOIN FieldReview ON Field.field_code=FieldReview.field_code WHERE Field.jp_num='$worker_email'");
        while($row1 =mysqli_fetch_array($getnickname1)) {
            $worker_email1[] = $row1[0];
            $field_code1 = $row1[1];
            $e++;
        }
        for($y=0; $y<$e; $y++){
            $getnickname2 = mysqli_query($con, "SELECT worker_name FROM Worker WHERE worker_email='$worker_email1[$y]'");
            while($row1 =mysqli_fetch_array($getnickname2)) {
                $worker_name1[] = $row1[0];
            }
        }
    }
    else if ($key1=='0'){
        $getfieldreview =  mysqli_query($con, "SELECT field_code FROM FieldReview WHERE worker_email='$worker_email'");
        $getofficereview =  mysqli_query($con, "SELECT business_reg_num FROM OfficeReview WHERE worker_email='$worker_email'");

    while($row1 =mysqli_fetch_array($getfieldreview)) {
        $field_code[] = $row1[0];
        $a++;
    }
    while($row1 =mysqli_fetch_array($getofficereview)) {
        $business_reg_num[] = $row1[0];
        $b++;
    }
    for($j=0; $j<$a; $j++){
        $statement = mysqli_query($con, "SELECT field_name FROM Field WHERE field_code='$field_code[$j]'");
        while($row1 =mysqli_fetch_array($statement)) {
            $field_name[] = $row1[0];
            $a++;
        }
    }
    for($i=0; $i<$b; $i++){
        $statement1 = mysqli_query($con, "SELECT manager_office_name FROM Manager WHERE business_reg_num='$business_reg_num[$i]'");
        while($row1 =mysqli_fetch_array($statement1)) {
            $office_name[] = $row1[0];
            $b++;
        }
    }

    }
    

    
    if($key1 == 0){
         $k=0;
        $getfieldreview1 =  mysqli_query($con, "SELECT fr_contents, fr_datetime FROM FieldReview WHERE worker_email='$worker_email'");
        while($row =mysqli_fetch_array($getfieldreview1)) {
            array_push($response, array('name'=>$field_name[$k],'contents'=>$row[0],'datetime'=>$row[1],'key'=>"0", 'ForOInfo'=>$field_code[$k]));
            $k++;
        }
    
         $i=0;
        $getofficereview4 =  mysqli_query($con, "SELECT or_contents, or_datetime FROM OfficeReview WHERE worker_email='$worker_email'");
        while($row =mysqli_fetch_array($getofficereview4)) {
            array_push($response, array('name'=>$office_name[$i],'contents'=>$row[0],'datetime'=>$row[1],'key'=>"1", 'ForOInfo'=>$business_reg_num[$i]));
            $i++;
        }
    }
    else if($key1 == 1){
        $i=0;
        $getofficereview3 =  mysqli_query($con, "SELECT or_contents, or_datetime FROM OfficeReview WHERE business_reg_num='$worker_email'");
        while($row =mysqli_fetch_array($getofficereview3)) {
            array_push($response, array('name'=>$worker_name11[$i],'contents'=>$row[0],'datetime'=>$row[1]));
            $i++;
        }

    }else if($key1 == 2){
        $k=0;
        $getfieldreview2 =  mysqli_query($con, "SELECT fr_contents, fr_datetime FROM FieldReview WHERE field_code='$field_code1'");
        while($row =mysqli_fetch_array($getfieldreview2)) {
            array_push($response, array('name'=>$worker_name1[$k],'contents'=>$row[0],'datetime'=>$row[1]));
            $k++;
        }
    }
    

    echo json_encode(array('response'=>$response), JSON_UNESCAPED_UNICODE);
?>