<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<?php
    header('Content-Type: application/json; charset=utf-8');
    $con = mysqli_connect("localhost", "rickshaw", "capstone1!", "rickshaw");
    if($con)
    {
    echo " connect : success 성공 <br>";
    }
    else
    {
    echo "disconnect : fail <br>";
    }

    $response = array();
    $response1 = array();
    $response2 = array();

    // 시도 조회
    $selectSido = mysqli_query($con, "SELECT DISTINCT local_sido FROM Local");
    while($row = mysqli_fetch_assoc($selectSido)){
        $response[] = $row;
    }


    for($i=0; $i<count($response);$i++)
    {
        $sido[] = $response[$i]["local_sido"];
        $sigugun = mysqli_query($con,"SELECT local_sigugun FROM Local WHERE local_sido = '$sido[$i]' ORDER BY local_sigugun ASC");
        while($row = mysqli_fetch_assoc($sigugun)){  
            $response1[] = $row;
        }
    }   

    for($i=0; $i<count($response);$i++)
    {
        $sido2[] = $response[$i]["local_sido"];
        $sigugunNum = mysqli_query($con,"SELECT local_sido, COUNT(local_sigugun) AS singugunNum FROM Local GROUP BY local_sido HAVING local_sido='$sido2[$i]'");
        while($row = mysqli_fetch_assoc($sigugunNum)){  
            $response2[] = $row;
        }
    }   

/*
    $sigugunNum = mysqli_query($con, "SELECT local_sido, COUNT(local_sigugun) FROM Local GROUP BY local_sido");


    // 시구군 조회
    $selectSigugun = mysqli_query($con, "SELECT local_sigugun FROM Local WHERE local_sido IN (SELECT DISTINCT local_sido FROM Local)");
    while($row = mysqli_fetch_assoc($selectSigugun)){
        $response1[] = $row;
    }
*/
    echo json_encode($response ,JSON_UNESCAPED_UNICODE);
    echo json_encode($response1,JSON_UNESCAPED_UNICODE);
    echo json_encode($response2 ,JSON_UNESCAPED_UNICODE);
    mysqli_close($con);
?>