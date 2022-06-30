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

    //$worker_email = 'miney8332@naver.com';
    //$phpdate = '2020-10-11';
    //$phpdate1 = '2020-11-11';
    $worker_email = $_POST['worker_email'];
    $phpdate = $_POST['phpdate'];
    $phpdate1 = $_POST['phpdate1'];
    $business_reg_num=[];
    $job_code=[];
    $jp_title=[];
    $jp_job_date=[];

    // 지원한 구인글의 jp_num SELECT
   

    //$selectJP = mysqli_query($con, "SELECT JobPosting.*  FROM MyField JOIN JobPosting ON JobPosting.jp_num = MyField.jp_num WHERE  MyField.worker_email = '$worker_email' AND  MyField.mf_is_choolgeun = 1 AND  MyField.mf_is_toigeun = 1");

    
    $selectJP1 = mysqli_query($con, "SELECT JobPosting.business_reg_num, JobPosting.job_code, JobPosting.jp_num
    FROM JobPosting JOIN MyField ON JobPosting.jp_num = MyField.jp_num WHERE MyField.worker_email = '$worker_email' AND  MyField.mf_is_choolgeun = 1 AND  MyField.mf_is_toigeun = 1 AND JobPosting.jp_job_date >= '$phpdate' AND JobPosting.jp_job_date<= '$phpdate1'");

    $a = 0;
    if (!empty($selectJP1) || $selectJP1 == true){ //변환이 필요한 값들 먼저 갖고오기
        while($row1 =mysqli_fetch_array($selectJP1)) {
            $business_reg_num[] = $row1[0];
            $job_code[] = $row1[1]; //
            $jp_num[] = $row1[2];
            
            $a++;
        }
    }

    $job_name=[];
    $field_address=[];
    $field_name=[];
    $office_name=[];

    for($k=0; $k<$a; $k++){
        // 직종 이름 검색
        $selectJN = mysqli_query($con, "SELECT job_name FROM Job WHERE job_code='$job_code[$k]'");
        if (!empty($selectJN) || $selectJN == true){
            while($row =mysqli_fetch_array($selectJN)) {
                //$job_name[]= $row[0];
                $job_name[]= $row['job_name'];
            }
        }
        // 현장 주소 검색
        
        $selectFldAd1 = mysqli_query($con, "SELECT field_name, field_address FROM Field WHERE jp_num = '$jp_num[$k]'");
        if (!empty($selectFldAd1) || $selectFldAd1 == true){
            while($row =mysqli_fetch_array($selectFldAd1)) {
                //$field_address[] = $row[0];
                $field_name[] = $row['field_name'];
                $field_address[] = $row['field_address'];
            }
        }
        $selectFldAd2 = mysqli_query($con, "SELECT manager_office_name FROM Manager WHERE business_reg_num = '$business_reg_num[$k]'");
        if (!empty($selectFldAd2) || $selectFldAd2 == true){
            while($row =mysqli_fetch_array($selectFldAd2)) {
                //$field_address[] = $row[0];
                $office_name[] = $row['manager_office_name'];
            }
        }
    }

    $selectJP = mysqli_query($con, "SELECT JobPosting.jp_title,JobPosting.jp_is_urgency,JobPosting.jp_contents,JobPosting.jp_num, JobPosting.jp_job_cost, JobPosting.jp_job_tot_people, JobPosting.jp_job_date,JobPosting.jp_job_start_time, JobPosting.jp_job_finish_time,  MyField.mf_is_paid
    FROM JobPosting JOIN MyField ON JobPosting.jp_num = MyField.jp_num WHERE MyField.worker_email = '$worker_email' AND  MyField.mf_is_choolgeun = 1 AND  MyField.mf_is_toigeun = 1 AND JobPosting.jp_job_date >= '$phpdate' AND JobPosting.jp_job_date<= '$phpdate1'");

    $j=0;
    if (!empty($selectJP) || $selectJP == true){
        while($row =mysqli_fetch_array($selectJP)) {
            array_push($response, array('business_reg_num'=>$business_reg_num[$j],'jp_title'=>$row['jp_title'],'jp_num'=>$row['jp_num'],'job_name'=>$job_name[$j],'jp_is_urgency'=>$row['jp_is_urgency'],'jp_contents'=>$row['jp_contents'],'jp_job_tot_people'=>$row['jp_job_tot_people'],'jp_job_start_time'=>$row['jp_job_start_time'],'jp_job_finish_time'=>$row['jp_job_finish_time'], 'jp_job_cost'=>$row['jp_job_cost'], 'jp_job_date'=>$row['jp_job_date'], 'office_name'=>$office_name[$j], 'field_name'=>$field_name[$j], 'field_address'=>$field_address[$j], 'mf_is_paid'=>$row['mf_is_paid']));
            $j++;
        }
    }
    

    echo json_encode(array('response'=>$response), JSON_UNESCAPED_UNICODE);
?>