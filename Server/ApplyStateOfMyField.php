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
    $business_reg_num=[];
    $job_code=[];
    $jp_title=[];
    $jp_job_cost=[];
    $jp_job_date=[];
    $jp_job_start_time=[];
    $jp_job_finish_time=[];
    $jp_is_urgency=[];
    $apply_is_picked=[];
    $jp_job_tot_people=[];
    $jp_contents=[];
    $jp_datetime=[];

    // 지원한 구인글의 jp_num SELECT
    $selectJP = mysqli_query($con, "SELECT JobPosting.business_reg_num, JobPosting.job_code, JobPosting.jp_title, JobPosting.jp_job_cost, JobPosting.jp_job_date, 
                                    JobPosting.jp_job_start_time, JobPosting.jp_job_finish_time, JobPosting.jp_is_urgency, Apply.jp_num, Apply.apply_is_picked, 
                                    JobPosting.jp_job_tot_people, JobPosting.jp_contents, JobPosting.jp_datetime
                                    FROM JobPosting JOIN Apply ON JobPosting.jp_num = Apply.jp_num WHERE Apply.worker_email = '$worker_email' AND DATE(JobPosting.jp_job_date) >= CURDATE()");

    $selectJP1 = mysqli_query($con, "SELECT JobPosting.job_code, Apply.jp_num, JobPosting.business_reg_num
    FROM JobPosting JOIN Apply ON JobPosting.jp_num = Apply.jp_num WHERE Apply.worker_email = '$worker_email' AND DATE(JobPosting.jp_job_date) >= CURDATE()");

    $a = 0;
    if (!empty($selectJP1) || $selectJP1 == true){ //변환이 필요한 값들 먼저 갖고오기
        while($row1 =mysqli_fetch_array($selectJP1)) {
            $job_code[] = $row1[0];
            $jp_num[] = $row1[1];
            $business_reg_num[] = $row1[2];
            $a++;
        }
    }

    $job_name=[];
    $field_address=[];
    $field_name=[];
    $jp_job_current_people=[];
    $manager_office_name=[];
    for($k=0; $k<$a; $k++){
        // 직종 이름 검색
        $selectJN = mysqli_query($con, "SELECT job_name FROM Job WHERE job_code='$job_code[$k]'");
        if (!empty($selectJN) || $selectJN == true){
            while($row =mysqli_fetch_array($selectJN)) {
                $job_name[]= $row['job_name'];
            }
        }
        // 현장 주소, 이름 검색
        $selectFld = mysqli_query($con, "SELECT Field.field_address, Field.field_name FROM Field WHERE jp_num = '$jp_num[$k]'");
        if (!empty($selectFld) || $selectFld == true){
            while($row =mysqli_fetch_array($selectFld)) {
                $field_address[] = $row['field_address'];
                $field_name[] = $row['field_name'];
            }
        }
        // 선발된 인원 검색
        $selectCrntP = mysqli_query($con, "SELECT COUNT(*) FROM Apply WHERE jp_num = '$jp_num[$k]' AND apply_is_picked=1");
        if (!empty($selectCrntP) || $selectCrntP == true){
           while($row =mysqli_fetch_array($selectCrntP)) {
               $jp_job_current_people[]= $row[0];
            }
        }
        // 인력사무소 이름 검색
        $selectOffcNm = mysqli_query($con, "SELECT manager_office_name FROM Manager WHERE business_reg_num = '$business_reg_num[$k]'");
        if (!empty($selectOffcNm) || $selectOffcNm == true){
            while($row =mysqli_fetch_array($selectOffcNm)) {
                $manager_office_name[]= $row[0];
            }
         } 
    }
    $j=0;
    if (!empty($selectJP) || $selectJP == true){
        while($row =mysqli_fetch_array($selectJP)) {
            array_push($response, array('business_reg_num'=>$row[0], 'jp_title'=>$row[2], 'jp_job_cost'=>$row[3], 'jp_job_date'=>$row[4], 'jp_job_start_time'=>$row[5], 
                                        'jp_job_finish_time'=>$row[6], 'jp_is_urgency'=>$row[7],'jp_num'=>$row[8],'apply_is_picked'=>$row[9], 'jp_job_tot_people'=>$row[10], 
                                        'jp_contents'=>$row[11], 'jp_datetime'=>$row[12], 'job_name'=>$job_name[$j], 'field_address'=>$field_address[$j], 'field_name'=>$field_name[$j],
                                        'jp_job_current_people'=>$jp_job_current_people[$j], 'manager_office_name'=>$manager_office_name[$j]));
            $j++;
        }
    }
    echo json_encode(array('response'=>$response), JSON_UNESCAPED_UNICODE);
?>