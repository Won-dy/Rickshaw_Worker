<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<?php
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
    $worker_email = $_POST['worker_email'];
    $worker_pw =  $_POST['worker_pw'];
 //$worker_email = "miney8332@naver.com";
   //$worker_pw = "wjdtjsdn2@";
   $response = [];
   $response["tryLogin"]=false;
   echo $worker_email;
    echo $worker_pw;
   $statement = mysqli_query($con, "SELECT worker_email, worker_pw, worker_name, worker_gender, worker_birth, worker_phonenum, worker_introduce, worker_bankaccount, worker_bankname, worker_is_approved FROM Worker WHERE worker_email = '$worker_email' and worker_pw = '$worker_pw'");
    while($row = mysqli_fetch_array($statement)) {
        echo $row;
        $response["tryLogin"] = true;
        $response["worker_email"] = $row[0];
        $response["worker_pw"] = $row[1];
        $response["worker_name"] = $row[2];
        $response["worker_gender"] = $row[3];
        $response["worker_birth"] = $row[4];
        $response["worker_phonenum"] = $row[5];
        $response["worker_introduce"] = $row[6];
        $response["worker_bankaccount"] = $row[7];
        $response["worker_bankname"] = $row[8];
        $response["worker_is_approved"] = $row[9];
   }
    
  
    //mysql_free_result($con);
    $data = mysqli_query($con, "SELECT Job.job_name, HopeJob.hj_career, HopeJob.job_code FROM Job JOIN HopeJob ON HopeJob.job_code=Job.job_code WHERE HopeJob.worker_email = '$worker_email'") or die(mysqli_error($con));
    if (!empty($data) || $data == true){
        while($row =mysqli_fetch_array($data)) {
               array_push($response, array('jobname'=>$row[0], 'jobcareer'=>$row[1], 'job_code'=>$row[2]));
        }
    }
    //mysql_free_result($con);
    $data1 = mysqli_query($con, "SELECT Local.local_sido, Local.local_sigugun FROM HopeLocal JOIN Local ON HopeLocal.local_code=Local.local_code WHERE HopeLocal.worker_email = '$worker_email'") or die(mysqli_error($con));
    if (!empty($data1) || $data1 == true){
        while($row =mysqli_fetch_array($data1)) {
            $response["local_sido"] = $row[0];
            $response["local_sigugun"] = $row[1];
        }
    }
    echo json_encode(array('response'=>$response),JSON_UNESCAPED_UNICODE);
?>
