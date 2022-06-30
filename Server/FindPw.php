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

    // 클라이언트로부터 전송받은 worker_email 데이터 $worker_email에 저장
    $key = $_POST["key"];
    $worker_email = $_POST["worker_email"];
    $worker_name = $_POST["worker_name"];
    $worker_phonenum = $_POST["worker_phonenum"];

    if($key=="emailCheck"){  // 존재하는 email인가

        // SQL문 준비 - worker_email 검색
        $statement = mysqli_prepare($con, "SELECT worker_email FROM Worker WHERE worker_email = ?");

        // $userID(파라미터)를 $statement(sql문)에 바인딩
        mysqli_stmt_bind_param($statement, "s", $worker_email);  // sql문, 형식, PHP 변수
        mysqli_stmt_execute($statement);  // 실행
    

        // array로 결과 출력
        $response["isExistEmail"] = false;
    
        // 검색한 실행 결과를 리턴 - 값이 있으면 true 없으면 false 리턴
        while(mysqli_stmt_fetch($statement)) {  // 해당 ID가 이미 존재하면
            $response["isExistEmail"] = true;
            //$response["worker_email"] = $worker_email;
        }
    
    } else if($key=="workerCheck") {  // 존재하는 회원인가
        $worker_pw;

        $statement2 = mysqli_prepare($con, "SELECT worker_pw, worker_email  FROM Worker WHERE worker_email = ? and worker_name = ? and worker_phonenum = ?");

        mysqli_stmt_bind_param($statement2, "sss", $worker_email, $worker_name, $worker_phonenum);  // sql문, 형식, PHP 변수
        mysqli_stmt_execute($statement2);  // 실행

        mysqli_store_result($statement2);
        mysqli_stmt_bind_result($statement2, $worker_pw, $worker_email);
        
        $response["isExistWorker"] = false;

        while(mysqli_stmt_fetch($statement2)) {  // 해당 회원이 존재하면
            $response["isExistWorker"] = true;
            $response["worker_pw"] = $worker_pw;
            $response["worker_email"] = $worker_email;
        }
        
    }

    // json코드로 클라이언트에 리턴(응답)
    echo json_encode($response);
?>