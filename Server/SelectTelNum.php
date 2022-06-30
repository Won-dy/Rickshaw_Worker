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
    $business_reg_num = $_POST["business_reg_num"];
    //$business_reg_num = "9876543210";
    $manager_office_telnum;
    $manager_phonenum;

    $selectTelNum = mysqli_prepare($con, "SELECT manager_office_telnum, manager_phonenum FROM Manager WHERE business_reg_num = '$business_reg_num'");
    mysqli_stmt_execute($selectTelNum);

    mysqli_store_result($selectTelNum);
    mysqli_stmt_bind_result($selectTelNum, $manager_office_telnum, $manager_phonenum);

    $response["selectTelNum"] = false;

    while(mysqli_stmt_fetch($selectTelNum)) {
        $response["selectTelNum"] = true;
        $response["manager_office_telnum"] = $manager_office_telnum;
        $response["manager_phonenum"] = $manager_phonenum;
    }


    echo json_encode($response);
    
?>