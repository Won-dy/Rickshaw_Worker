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

    $key =  $_POST['key'];
    $worker_email = $_POST['worker_email'];
    $jp_num = $_POST['jp_num'];

    if($key == "checkStart") {

        $update_is_chool = mysqli_prepare($con, "UPDATE MyField SET mf_is_choolgeun = '1' WHERE worker_email = ? AND jp_num = ?");
        
        mysqli_stmt_bind_param($update_is_chool, "ss", $worker_email, $jp_num);
        if(mysqli_stmt_execute($update_is_chool))
            $response["checkStartSuccess"] = "true";
        else
            $response["checkStartSuccess"] = mysqli_error($con);
    }
    else if($key == "checkFinish") {

        $update_is_toi = mysqli_prepare($con, "UPDATE MyField SET mf_is_toigeun = '1' WHERE worker_email = ? AND jp_num = ?");
        
        mysqli_stmt_bind_param($update_is_toi, "ss", $worker_email, $jp_num);
        if(mysqli_stmt_execute($update_is_toi))
            $response["checkFinishSuccess"] = "true";
        else
            $response["checkFinishSuccess"] = mysqli_error($con);

    }
    else if($key == "LoadChoolToi") {  // 출퇴근 여부 로드

        $mf_is_choolgeun;
        $mf_is_toigeun;

        // 지원한 구인글의 jp_num SELECT
        $select_is_CT = mysqli_prepare($con, "SELECT mf_is_choolgeun, mf_is_toigeun FROM MyField WHERE worker_email = ? AND jp_num = ?");
        mysqli_stmt_bind_param($select_is_CT, "ss", $worker_email, $jp_num);
        mysqli_stmt_execute($select_is_CT);

        mysqli_store_result($select_is_CT);
        mysqli_stmt_bind_result($select_is_CT, $mf_is_choolgeun, $mf_is_toigeun);

        $response["select_CT"] = false;

        while(mysqli_stmt_fetch($select_is_CT)) {
            $response["select_CT"] = true;
            $response["mf_is_choolgeun"] = $mf_is_choolgeun;
            $response["mf_is_toigeun"] = $mf_is_toigeun;
        }
    }

    echo json_encode($response);
?>