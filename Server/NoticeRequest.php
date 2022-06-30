<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<?php
 header('Content-Type: application/json; charset=utf-8');
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
    $j=0;
    $board_content=[];
    $board_date=[];
    $board_title=[];
    $board_user=[];

    $statement1 =  mysqli_query($con, "SELECT * FROM board order by board_date desc");
    while($row1 =mysqli_fetch_array($statement1)){
        $board_date[] = $row1['board_date'];
        $board_user[] = $row1['board_user'];
        $board_content[] = $row1['board_content'];
        $board_title[] = $row1['board_title'];
        $j++;
    }
    
    // 지원한 구인글의 jp_num SELECT
    
    
     for($i=0; $i<$j; $i++){
        array_push($response, array('board_date'=>$board_date[$i], 'board_user'=>$board_user[$i], 'board_content'=>$board_content[$i], 'board_title'=>$board_title[$i]));
            
       }
     
    

    echo json_encode(array('response'=>$response));
?>