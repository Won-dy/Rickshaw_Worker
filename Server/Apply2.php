<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<?php
 header('Content-Type: application/json; charset=utf-8');
 
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

    $worker_email = $_POST['worker_email'];
    $jp_num = $_POST['jp_num'];
    $key = $_POST['key'];
    $AlreadyApply = false;
    $AlreadyPicked = false;

    $response["AlreadyApply"] = false;
    $response["AlreadyPicked"] = false;
    $response["InsertApplySuccess"] = false;
    $response["DeleteApplySuccess"] = false;
    
    $selectApply = mysqli_prepare($con, "SELECT * FROM Apply WHERE worker_email = ? AND jp_num = ?");

    mysqli_stmt_bind_param($selectApply, "ss", $worker_email, $jp_num);
    mysqli_stmt_execute($selectApply);
    
    while(mysqli_stmt_fetch($selectApply)) {
        $response["AlreadyApply"] = true;  // 이미 지원함
        $AlreadyApply = true;
    }

    if($key=="apply") { // 지원
        if(!($AlreadyApply)) {  // false면
            $InsertApply = mysqli_prepare($con, "INSERT INTO Apply(jp_num, worker_email) VALUES (?,?)");
            mysqli_stmt_bind_param($InsertApply, "ss", $jp_num, $worker_email);
        
            if(mysqli_stmt_execute($InsertApply))  // 추가 성공
                $response["InsertApplySuccess"] = true;
            else
                $response["InsertApplySuccess"] = false;
        }
    }

    if($key=="delete") {  // 지원 취소

        $selectPicked = mysqli_prepare($con, "SELECT * FROM Apply WHERE worker_email = ? AND jp_num = ? AND apply_is_picked = '1'");
        mysqli_stmt_bind_param($selectPicked, "ss", $worker_email, $jp_num);
        mysqli_stmt_execute($selectPicked);
        while(mysqli_stmt_fetch($selectPicked)) {  // 이미 선발됨
            $response["AlreadyPicked"] = true;
            $AlreadyPicked = true;
        }

        if($AlreadyApply && !($AlreadyPicked)) {
            $DeleteApply = mysqli_prepare($con, "DELETE FROM Apply WHERE worker_email = ? AND jp_num = ?");
            mysqli_stmt_bind_param($DeleteApply, "ss", $worker_email, $jp_num);
            if(mysqli_stmt_execute($DeleteApply))
                $response["DeleteApplySuccess"] = true;
        }
        
    }

    echo json_encode($response);
?>