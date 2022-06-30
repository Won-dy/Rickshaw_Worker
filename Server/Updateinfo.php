<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<?php
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
    $key = $_POST["key"];	

    if($key == "pnumIntroduce") {

        $worker_email = $_POST["worker_email"];
        $worker_phonenum = $_POST["worker_phonenum"];
        $worker_introduce = $_POST["worker_introduce"];

        $statement = mysqli_prepare($con, "UPDATE Worker SET worker_phonenum = ?, worker_introduce = ? WHERE worker_email = ?");

        mysqli_stmt_bind_param($statement, "sss", $worker_phonenum, $worker_introduce, $worker_email);
        mysqli_stmt_execute($statement);

        $response["a"] = true;

    } else if($key == "hopeLocal") {

        $local_code;
        $worker_email = $_POST["worker_email"];
        $local_sido = $_POST["local_sido"];
        $local_sigugun = $_POST["local_sigugun"];

        $selectstate = mysqli_prepare($con, "SELECT local_code  FROM Local WHERE local_sido = ? and local_sigugun = ?");
        mysqli_stmt_bind_param($selectstate, "ss", $local_sido , $local_sigugun);
        mysqli_stmt_execute($selectstate);
        
        mysqli_stmt_bind_result($selectstate, $local_code );
    
        while(mysqli_stmt_fetch($selectstate)) {
            $response["local_code"] = $local_code;
            $response["local_sido"] = $local_sido;
            $response["local_sigugun"] = $local_sigugun;
        }
    
        
        $response["updateSuccess"] = false;
        $statement2 = mysqli_prepare($con, "UPDATE HopeLocal SET local_code = ? WHERE worker_email = ?");
        mysqli_stmt_bind_param($statement2, "ss", $local_code, $worker_email);
        if(mysqli_stmt_execute($statement2))
            $response["updateSuccess"] = true;
        else
            $response["updateSuccess"] = false;

    } else if($key == "hopeJobCareer") {

        $worker_email =$_POST["worker_email"];  
        $job_code = $_POST["job_code"];
        $hj_career = $_POST["hj_career"];
        $a = $_POST["k"];

        if($a==0){
            $data5 = mysqli_query($con, "DELETE FROM HopeJob WHERE worker_email='$worker_email'");
        }


        $data1 = mysqli_query($con, "SELECT job_name FROM Job WHERE job_code = '$job_code'") or die(mysqli_error($con));
        while($row =mysqli_fetch_array($data1)) {
            $job_name = $row[0];
        }
        
        
            $data2 = mysqli_query($con, "INSERT INTO HopeJob VALUES ('$worker_email','$job_code','$hj_career')") or die(mysqli_error($con));
        
            $response['job_code'] = $job_code;
            $response['hj_career'] = $hj_career;
            $response['job_name'] = $job_name;

        

        
        /*
        $statement3 = mysqli_prepare($con, "UPDATE HopeJob SET job_code = ? and hj_career = ? WHERE worker_email = ?");
        mysqli_stmt_bind_param($statement3, "sis", $worker_email, $job_codeint, $hj_career);
        if(mysqli_stmt_execute($statement3))
            $response["updateSuccess2"] = "true";
        else
            $response["updateSuccess2"] = mysqli_error($con);
        */
    }
    else if ($key == "accountUpdate") {

        $worker_email =$_POST["worker_email"];  
        $worker_bankname =$_POST["worker_bankname"];  
        $worker_bankaccount =$_POST["worker_bankaccount"];

        $accountUpdate = mysqli_prepare($con, "UPDATE Worker SET worker_bankname = ?, worker_bankaccount = ? WHERE worker_email = ?");

        mysqli_stmt_bind_param($accountUpdate, "sss", $worker_bankname, $worker_bankaccount, $worker_email);

        if(mysqli_stmt_execute($accountUpdate))
            $response["updateSuccess3"] = true;
        else 
            $response["updateSuccess3"] = false;            

    } else if ($key == "loadJC") {

        $hopeJCNum;
        $worker_email =$_POST["worker_email"];

        $loadJC = mysqli_prepare($con, "SELECT COUNT(*) FROM HopeJob WHERE worker_email = '$worker_email'");
        mysqli_stmt_execute($loadJC);
    
        mysqli_store_result($loadJC);
        mysqli_stmt_bind_result($loadJC, $hopeJCNum);
    
        $response["select_hopeJCNum"] = false;
    
        while(mysqli_stmt_fetch($loadJC)) {
            $response["select_hopeJCNum"] = true;
            $response["hopeJCNum"] = $hopeJCNum;
        }
    } else if ($key == "deleteAccnt") {

        $worker_email =$_POST["worker_email"];

        $deleteAccnt = mysqli_prepare($con, "UPDATE Worker SET worker_bankname = '', worker_bankaccount = '' WHERE worker_email = ?");

        mysqli_stmt_bind_param($deleteAccnt, "s",$worker_email);

        if(mysqli_stmt_execute($deleteAccnt))
            $response["deleteSuccess"] = true;
        else 
            $response["deleteSuccess"] = false;            
    }

    echo json_encode($response);
    mysqli_close($con);
?>