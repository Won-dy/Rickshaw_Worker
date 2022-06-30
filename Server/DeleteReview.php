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

    $key = $_POST['key'];
    $response["DeleteRVSuccess"] = false;

    if($key=="WR") { // 구직자 리뷰 삭제

        $business_reg_num = $_POST['business_reg_num'];
        $jp_num = $_POST['jp_num'];
        $worker_email = $_POST['worker_email'];
        $dt = $_POST['dt'];

        $DeleteWR = mysqli_prepare($con, "DELETE FROM WorkerReview WHERE business_reg_num = ? AND worker_email = ? AND jp_num = ? AND wr_datetime = ?");
        mysqli_stmt_bind_param($DeleteWR, "ssss", $business_reg_num, $worker_email, $jp_num, $dt);
        if(mysqli_stmt_execute($DeleteWR))
            $response["DeleteRVSuccess"] = true;

    } else if ($key=="FR") { // 현장 리뷰 삭제

        $ForOInfo = $_POST['ForOInfo'];
        $worker_email = $_POST['worker_email'];
        $dt = $_POST['dt'];

        $DeleteFR = mysqli_prepare($con, "DELETE FROM FieldReview WHERE field_code = ? AND worker_email = ? AND fr_datetime = ?");
        mysqli_stmt_bind_param($DeleteFR, "sss", $ForOInfo, $worker_email, $dt);
        if(mysqli_stmt_execute($DeleteFR))
            $response["DeleteRVSuccess"] = true;

    } else if ($key=="OR") { // 사무소 리뷰 삭제

        $ForOInfo = $_POST['ForOInfo'];
        $worker_email = $_POST['worker_email'];
        $dt = $_POST['dt'];

        $DeleteOR = mysqli_prepare($con, "DELETE FROM OfficeReview WHERE business_reg_num = ? AND worker_email = ? AND or_datetime = ?");
        mysqli_stmt_bind_param($DeleteOR, "sss", $ForOInfo, $worker_email, $dt);
        if(mysqli_stmt_execute($DeleteOR))
            $response["DeleteRVSuccess"] = true;

    }  
        
    echo json_encode($response);
?>
