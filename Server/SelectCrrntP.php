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

    $jp_num = $_POST['jp_num'];
    $data = mysqli_query($con,  "SELECT COUNT(*) FROM Apply WHERE jp_num = '$jp_num' AND apply_is_picked= 1") or die(mysqli_error($con));
        while($row =mysqli_fetch_array($data)) {
            $response['current_people'] = $row[0];
        }
    


    echo json_encode($response);
?>
