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

    //$worker_email = 'miney8332@naver.com';
    //$phpdate = '2020-10-11';
    //$phpdate1 = '2020-11-11';
    $worker_email = $_POST['worker_email'];
    $contents = $_POST['contents'];
    $jp_num = $_POST['jp_num'];
    $key = $_POST['key'];

    if($key == 0){
        $statement1 =  mysqli_query($con, "SELECT field_code FROM Field WHERE jp_num = '$jp_num'");
        while($row1 =mysqli_fetch_array($statement1)){
            $field_code = $row1[0];
        }
        $statement =  mysqli_query($con, "INSERT INTO FieldReview(field_code, worker_email, fr_contents) VALUES('$field_code', '$worker_email', '$contents')");
    }
    else {
        $statement =  mysqli_query($con, "INSERT INTO OfficeReview(business_reg_num, worker_email, or_contents) VALUES('$jp_num', '$worker_email', '$contents')");
    }
    
    
    
    // 지원한 구인글의 jp_num SELECT
    
    
     
    

    echo json_encode();
?>