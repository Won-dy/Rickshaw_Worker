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

    $business_reg_num =  $_POST['business_reg_num'];

    $manager_office_name;
    $manager_office_telnum;
    $manager_office_address;
    $manager_name;
    $manager_phonenum;
    $manager_office_info;

    $select_manager = mysqli_prepare($con, "SELECT manager_office_name, manager_office_telnum, manager_office_address, manager_name, manager_phonenum, manager_office_info FROM Manager WHERE business_reg_num = ?");
    mysqli_stmt_bind_param($select_manager, "s", $business_reg_num);
    mysqli_stmt_execute($select_manager);

    mysqli_store_result($select_manager);
    mysqli_stmt_bind_result($select_manager, $manager_office_name, $manager_office_telnum, $manager_office_address, $manager_name, $manager_phonenum, $manager_office_info);

    $response["select_Mng"] = false;

    while(mysqli_stmt_fetch($select_manager)) {
        $response["select_Mng"] = true;
        $response["manager_office_name"] = $manager_office_name;
        $response["manager_office_telnum"] = $manager_office_telnum;
        $response["manager_office_address"] = $manager_office_address;
        $response["manager_name"] = $manager_name;
        $response["manager_phonenum"] = $manager_phonenum;
        $response["manager_office_info"] = $manager_office_info;
    }

    echo json_encode($response);
?>