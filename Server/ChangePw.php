<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<?php
    // 서버와 DB에 접속
    // $con = mysqli_connect(host, username(id), passwd, DB명);
    $con = mysqli_connect("localhost", "rickshaw", "capstone1!", "rickshaw");
    if (mysqli_connect_errno($con)) { echo "DB 접속 실패 failed"; } else { echo "DB 접속 성공 seccessed"; }

    $response = array();
    $key = $_POST["key"];

    if($key == "worker") {

        $worker_email = $_POST["worker_email"];
        $worker_pw =  $_POST["worker_pw"];
        $worker_new_pw =  $_POST["worker_new_pw"];
        $worker_check_new_pw =  $_POST["worker_check_new_pw"];
        $isExistPw = false;

        $statement2 = mysqli_prepare($con, "SELECT worker_pw FROM Worker WHERE worker_email = ? and worker_pw = ?");

        mysqli_stmt_bind_param($statement2, "ss", $worker_email, $worker_pw);
        mysqli_stmt_execute($statement2);  // 실행

        $response["isExistPw"] = false;
        $response["pwChangeSuccess"] = false;
        
        while(mysqli_stmt_fetch($statement2)) {  // 현재 비밀번호가 맞으면
            $response["isExistPw"] = true;
            if($worker_new_pw == $worker_check_new_pw) {
                $isExistPw = true;
            }
        }

        if($isExistPw) {

            $statement = mysqli_prepare($con, "UPDATE Worker SET worker_pw = ? WHERE worker_email = ? and worker_pw = ?");

            mysqli_stmt_bind_param($statement, "sss", $worker_new_pw, $worker_email, $worker_pw);
            mysqli_stmt_execute($statement);  // 실행

            $response["pwChangeSuccess"] = true;
        }

    } else if($key == "manager") {

        $business_reg_num = $_POST["business_reg_num"];
        $manager_pw =  $_POST["manager_pw"];
        $manager_new_pw =  $_POST["manager_new_pw"];
        $manager_check_new_pw =  $_POST["manager_check_new_pw"];
        $isExistPw = false;

        $statement2 = mysqli_prepare($con, "SELECT manager_pw FROM Manager WHERE business_reg_num = ? and manager_pw = ?");

        mysqli_stmt_bind_param($statement2, "ss", $business_reg_num, $manager_pw);
        mysqli_stmt_execute($statement2);  // 실행

        $response["isExistPw"] = false;
        $response["pwChangeSuccess"] = false;
        
        while(mysqli_stmt_fetch($statement2)) {  // 현재 비밀번호가 맞으면
            $response["isExistPw"] = true;
            if($manager_new_pw == $manager_check_new_pw) {
                $isExistPw = true;
            }
        }

        if($isExistPw) {

            $statement = mysqli_prepare($con, "UPDATE Manager SET manager_pw = ? WHERE business_reg_num = ? and manager_pw = ?");

            mysqli_stmt_bind_param($statement, "sss", $manager_new_pw, $business_reg_num, $manager_pw);
            mysqli_stmt_execute($statement);  // 실행

            $response["pwChangeSuccess"] = true;
        }
    }

    // json코드로 클라이언트에 리턴(응답)
    echo json_encode($response);
?>