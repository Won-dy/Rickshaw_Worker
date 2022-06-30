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

    if($key == "worker") {

        $worker_email = $_POST["worker_email"];
        $worker_pw =  $_POST["worker_pw"];
        $worker_check_pw =  $_POST["worker_check_pw"];
        $isExistPw = false;

        $statement2 = mysqli_prepare($con, "SELECT worker_pw FROM Worker WHERE worker_email = ? and worker_pw = ?");

        mysqli_stmt_bind_param($statement2, "ss", $worker_email, $worker_pw);
        mysqli_stmt_execute($statement2);  // 실행

        $response["isExistPw"] = false;
        $response["RemoveSuccess"] = false;
        
        while(mysqli_stmt_fetch($statement2)) {  // 현재 비밀번호가 맞으면
            $response["isExistPw"] = true;
            if($worker_pw == $worker_check_pw) {
                $isExistPw = true;
            }
        }

        if($isExistPw) {

            $statement = mysqli_prepare($con, "DELETE FROM Worker WHERE worker_email = ? and worker_pw = ?");

            mysqli_stmt_bind_param($statement, "ss", $worker_email, $worker_pw);
            mysqli_stmt_execute($statement);  // 실행

            $response["RemoveSuccess"] = true;
        }

    } else if($key == "manager") {

        $business_reg_num = $_POST["business_reg_num"];
        $manager_pw =  $_POST["manager_pw"];
        $manager_check_pw =  $_POST["manager_check_pw"];
        $isExistPw = false;

        $statement2 = mysqli_prepare($con, "SELECT manager_pw FROM Manager WHERE business_reg_num = ? and manager_pw = ?");

        mysqli_stmt_bind_param($statement2, "ss", $business_reg_num, $manager_pw);
        mysqli_stmt_execute($statement2);  // 실행

        $response["isExistPw"] = false;
        $response["RemoveSuccess"] = false;
        
        while(mysqli_stmt_fetch($statement2)) {  // 현재 비밀번호가 맞으면
            $response["isExistPw"] = true;
            if($manager_pw == $manager_check_pw) {
                $isExistPw = true;
            }
        }

        if($isExistPw) {

            $statement = mysqli_prepare($con, "DELETE FROM Manager WHERE business_reg_num = ? and manager_pw = ?");

            mysqli_stmt_bind_param($statement, "ss", $business_reg_num, $manager_pw);
            mysqli_stmt_execute($statement);  // 실행

            $response["RemoveSuccess"] = true;
        }
    }

    // json코드로 클라이언트에 리턴(응답)
    echo json_encode($response);
?>