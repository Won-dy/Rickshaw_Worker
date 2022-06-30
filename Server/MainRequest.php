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
// worker_email로 local_code와 job_code 받아와서 jobposting에서 일치하는 일들 갖고오기
   $worker_email = $_POST['worker_email'];
    $post_localsido = $_POST['local_sido'];
    $post_localsigugun = $_POST['local_sigugun'];
    $post_jobcode0 = $_POST['jobname0'];
    $post_jobcode1 = $_POST['jobname1'];
    $post_jobcode2 = $_POST['jobname2'];
    $search = $_POST['search'];

 //   $worker_email = "0";
 //    $post_localsido = "서울";
 //    $post_localsigugun = "0";
 //    $post_jobcode0 = "0";
 //    $post_jobcode1 = "0";
  //  $post_jobcode2 = "0";
 // $search = "0";

    $response = array();


    if(!empty($worker_email)){ // 메인 처음으로 들어왔을 때
        $job_code=[];
        
        $i =0;
        $data = mysqli_query($con, "SELECT job_code FROM HopeJob WHERE worker_email = '$worker_email'") or die(mysqli_error($con));
        if (!empty($data) || $data == true){
            while($row =mysqli_fetch_array($data)) {
                $jobcode[] = $row['job_code'];
	            $i++;
            }
        }
   
        //mysql_free_result($con);
        $data1 = mysqli_query($con, "SELECT local_code FROM HopeLocal WHERE worker_email = '$worker_email'") or die(mysqli_error($con));
        if (!empty($data1) || $data1 == true){
            while($row =mysqli_fetch_array($data1)) {
                $local_code = $row['local_code'];
            }
        }
        if($i == 3){
            $data2 = mysqli_query($con, "SELECT jp_num, business_reg_num, local_code, jp_title, jp_contents, jp_job_cost, jp_job_tot_people, jp_job_date, jp_job_start_time, jp_job_finish_time, jp_is_urgency, jp_datetime, job_code FROM JobPosting WHERE local_code = '$local_code' AND ( job_code='$jobcode[0]' OR job_code='$jobcode[1]' OR job_code='$jobcode[2]') ORDER BY jp_job_date") or die(mysqli_error($con));
            $data5 = mysqli_query($con, "SELECT jp_num, local_code, job_code, business_reg_num FROM JobPosting WHERE local_code = '$local_code' AND( job_code='$jobcode[0]' OR job_code='$jobcode[1]' OR job_code='$jobcode[2]') ORDER BY jp_job_date") or die(mysqli_error($con));
        }
        else if($i==2){
            $data2 = mysqli_query($con, "SELECT jp_num, business_reg_num, local_code, jp_title, jp_contents, jp_job_cost, jp_job_tot_people, jp_job_date, jp_job_start_time, jp_job_finish_time, jp_is_urgency, jp_datetime, job_code FROM JobPosting WHERE local_code = '$local_code' AND( job_code='$jobcode[0]' OR job_code='$jobcode[1]') ORDER BY jp_job_date") or die(mysqli_error($con));
            $data5 = mysqli_query($con, "SELECT jp_num, local_code, job_code, business_reg_num FROM JobPosting WHERE local_code = '$local_code' AND( job_code='$jobcode[0]' OR job_code='$jobcode[1]') ORDER BY jp_job_date") or die(mysqli_error($con));
        }
        else {
            $data2 = mysqli_query($con, "SELECT jp_num, business_reg_num, local_code, jp_title, jp_contents, jp_job_cost, jp_job_tot_people, jp_job_date, jp_job_start_time, jp_job_finish_time, jp_is_urgency, jp_datetime, job_code FROM JobPosting WHERE local_code = '$local_code' OR job_code='$jobcode[0]' ORDER BY jp_job_date") or die(mysqli_error($con));
            $data5 = mysqli_query($con, "SELECT jp_num, local_code, job_code, business_reg_num FROM JobPosting WHERE local_code = '$local_code' AND job_code='$jobcode[0]' ORDER BY jp_job_date") or die(mysqli_error($con));    
        }
    }
    else if(empty($worker_email)  AND !empty($search)){
        $data2 = mysqli_query($con, "SELECT JobPosting.jp_num, JobPosting.business_reg_num, JobPosting.local_code, JobPosting.jp_title, JobPosting.jp_contents, JobPosting.jp_job_cost, JobPosting.jp_job_tot_people, JobPosting.jp_job_date, JobPosting.jp_job_start_time, JobPosting.jp_job_finish_time, JobPosting.jp_is_urgency, JobPosting.jp_datetime, JobPosting.job_code FROM JobPosting JOIN Manager ON JobPosting.business_reg_num = Manager.business_reg_num WHERE jp_title LIKE '%$search%' OR manager_office_name LIKE '%$search%' ORDER BY JobPosting.jp_job_date ") or die(mysqli_error($con));
        $data5 = mysqli_query($con, "SELECT JobPosting.jp_num, JobPosting.local_code, JobPosting.job_code, JobPosting.business_reg_num FROM JobPosting JOIN Manager ON JobPosting.business_reg_num = Manager.business_reg_num WHERE jp_title LIKE '%$search%' OR manager_office_name LIKE '%$search%' ORDER BY JobPosting.jp_job_date") or die(mysqli_error($con));

    }
    else  if(empty($worker_email)  AND $post_jobcode0 == 0 AND $post_localsido=="전체"){ // 직종, 로컬 선택을 안했을 경우

        $data2 = mysqli_query($con, "SELECT jp_num, business_reg_num, local_code, jp_title, jp_contents, jp_job_cost, jp_job_tot_people, jp_job_date, jp_job_start_time, jp_job_finish_time, jp_is_urgency, jp_datetime, job_code FROM JobPosting ORDER BY jp_job_date") or die(mysqli_error($con));
        $data5 = mysqli_query($con, "SELECT jp_num, local_code, job_code, business_reg_num FROM JobPosting ORDER BY jp_job_date") or die(mysqli_error($con));
        
    }
    else  if(empty($worker_email)  AND $post_jobcode0 != 0 AND $post_localsido=="전체"){ // 로컬 선택을 안하고 직종 선택을 했을 경우

        $data2 = mysqli_query($con, "SELECT jp_num, business_reg_num, local_code, jp_title, jp_contents, jp_job_cost, jp_job_tot_people, jp_job_date, jp_job_start_time, jp_job_finish_time, jp_is_urgency, jp_datetime, job_code FROM JobPosting WHERE job_code='$post_jobcode0' OR job_code='$post_jobcode1' OR job_code='$post_jobcode2' ORDER BY jp_job_date") or die(mysqli_error($con));
        $data5 = mysqli_query($con, "SELECT jp_num, local_code, job_code, business_reg_num FROM JobPosting WHERE job_code='$post_jobcode0' OR job_code='$post_jobcode1' OR job_code='$post_jobcode2' ORDER BY jp_job_date") or die(mysqli_error($con));
        
    }
    else  if(empty($worker_email) AND $post_jobcode0 == 0 AND $post_localsido != "전체" AND empty($post_localsigugun)){ // 직종선택을 안했을 경우, 첫번째 로컬만 선택했을 경우
        
        $data7 = mysqli_query($con, "SELECT local_code FROM Local WHERE local_sido='$post_localsido' ORDER BY RAND() LIMIT 1");
        if (!empty($data7) || $data7 == true){
            while($row =mysqli_fetch_array($data7)) {
                $local_code_value= $row[0];
           }
        }
        $local_code1 =  substr($local_code_value, 0, 2);

        $data2 = mysqli_query($con, "SELECT jp_num, business_reg_num, local_code, jp_title, jp_contents, jp_job_cost, jp_job_tot_people, jp_job_date, jp_job_start_time, jp_job_finish_time, jp_is_urgency, jp_datetime, job_code FROM JobPosting WHERE local_code LIKE '{$local_code1}%' ORDER BY jp_job_date") or die(mysqli_error($con));
        $data5 = mysqli_query($con, "SELECT jp_num, local_code, job_code, business_reg_num FROM JobPosting WHERE local_code LIKE '{$local_code1}%' ORDER BY jp_job_date") or die(mysqli_error($con));
        
    }
    else  if(empty($worker_email) AND $post_jobcode0 != 0 AND $post_localsido != "전체" AND empty($post_localsigugun)){ // 직종선택을 하고, 첫번째 로컬만 선택했을 경우 '%{$variable}%'
        
        $data7 = mysqli_query($con, "SELECT local_code FROM Local WHERE local_sido='$post_localsido' ORDER BY RAND() LIMIT 1");
        if (!empty($data7) || $data7 == true){
            while($row =mysqli_fetch_array($data7)) {
                $local_code_value= $row[0];
           }
        }
        $local_code1 =  substr($local_code_value, 0, 2);

        $data2 = mysqli_query($con, "SELECT jp_num, business_reg_num, local_code, jp_title, jp_contents, jp_job_cost, jp_job_tot_people, jp_job_date, jp_job_start_time, jp_job_finish_time, jp_is_urgency, jp_datetime, job_code FROM JobPosting WHERE local_code LIKE'{$local_code1}%' AND (job_code='$post_jobcode0' OR job_code='$post_jobcode1' OR job_code='$post_jobcode2') ORDER BY jp_job_date") or die(mysqli_error($con));
        $data5 = mysqli_query($con, "SELECT jp_num, local_code, job_code, business_reg_num FROM JobPosting WHERE local_code LIKE '{$local_code1}%' AND (job_code='$post_jobcode0' OR job_code='$post_jobcode1' OR job_code='$post_jobcode2') ORDER BY jp_job_date ") or die(mysqli_error($con));
        
    }
    else  if(empty($worker_email) AND $post_jobcode0 == 0 AND $post_localsido != "전체" AND !empty($post_localsigugun)){ // 직종선택을 안하고, 모든 로컬 선택
        
        $data7 = mysqli_query($con, "SELECT local_code FROM Local WHERE local_sido='$post_localsido' AND local_sigugun = '$post_localsigugun'");
        if (!empty($data7) || $data7 == true){
            while($row =mysqli_fetch_array($data7)) {
                $local_code_value= $row[0];
           }
        }

        $data2 = mysqli_query($con, "SELECT jp_num, business_reg_num, local_code, jp_title, jp_contents, jp_job_cost, jp_job_tot_people, jp_job_date, jp_job_start_time, jp_job_finish_time, jp_is_urgency, jp_datetime, job_code FROM JobPosting WHERE local_code='$local_code_value' ORDER BY jp_job_date") or die(mysqli_error($con));
        $data5 = mysqli_query($con, "SELECT jp_num, local_code, job_code, business_reg_num FROM JobPosting WHERE local_code = '$local_code_value' ORDER BY jp_job_date ") or die(mysqli_error($con));
        
    }
    else  if(empty($worker_email) AND $post_jobcode0 != 0 AND $post_localsido != "전체" AND !empty($post_localsigugun)){ // 모든 선택지 선택
        
        $data7 = mysqli_query($con, "SELECT local_code FROM Local WHERE local_sido='$post_localsido' AND local_sigugun = '$post_localsigugun'");
        if (!empty($data7) || $data7 == true){
            while($row =mysqli_fetch_array($data7)) {
                $local_code_value= $row[0];
           }
        }

        $data2 = mysqli_query($con, "SELECT jp_num, business_reg_num, local_code, jp_title, jp_contents, jp_job_cost, jp_job_tot_people, jp_job_date, jp_job_start_time, jp_job_finish_time, jp_is_urgency, jp_datetime, job_code FROM JobPosting WHERE local_code='$local_code_value' AND (job_code='$post_jobcode0' OR job_code='$post_jobcode1' OR job_code='$post_jobcode2') ORDER BY jp_job_date") or die(mysqli_error($con));
        $data5 = mysqli_query($con, "SELECT jp_num, local_code, job_code, business_reg_num FROM JobPosting WHERE local_code = '$local_code_value' AND (job_code='$post_jobcode0' OR job_code='$post_jobcode1' OR job_code='$post_jobcode2') ORDER BY jp_job_date") or die(mysqli_error($con));
    }

    $local_code=[];
    $job_code=[];
    $jp_num=[];
    $business_reg_num=[];
    $a = 0;
     if (!empty($data5) || $data5 == true){
        while($row1 =mysqli_fetch_array($data5)) {
            
            $local_code[] = $row1['local_code'];
            $job_code[] = $row1['job_code'];      
            $jp_num[] = $row1['jp_num'];  
            $business_reg_num[] = $row1['business_reg_num'];  
	        $a++; 
        }
    }
    
    $job_name=[];
    $field_address=[];
    $field_name=[];
    $field_code=[];
    $current_people=[];
    $manager_office_name=[];
    for($k=0; $k<$a; $k++){
         $data4 = mysqli_query($con, "SELECT job_name FROM Job WHERE job_code='$job_code[$k]'");
         if (!empty($data4) || $data4 == true){
            while($row =mysqli_fetch_array($data4)) {
            //array_push($response, array('job_name'=>$row[0]));
            $job_name[]= $row[0];
           }
          }
        $data6 = mysqli_query($con, "SELECT COUNT(*) FROM Apply WHERE jp_num = '$jp_num[$k]' AND apply_is_picked=1");
         if (!empty($data6) || $data6 == true){
        	while($row =mysqli_fetch_array($data6)) {
                    $current_people[]= $row[0];
           }
          }
        $data3 = mysqli_query($con, "SELECT Field.field_address, Field.field_name, Field.field_code FROM Field WHERE jp_num = '$jp_num[$k]'");
        if (!empty($data3) || $data3 == true){
        	while($row =mysqli_fetch_array($data3)) {
                $field_address[] = $row[0];
                $field_name[] = $row[1];
                $field_code[] = $row[2];
           }
          }
        $selectOffcNm = mysqli_query($con, "SELECT manager_office_name FROM Manager WHERE business_reg_num = '$business_reg_num[$k]'");
        if (!empty($selectOffcNm) || $selectOffcNm == true){
            while($row =mysqli_fetch_array($selectOffcNm)) {
                $manager_office_name[]= $row[0];
            }
        } 
    }
    $j=0;
    if (!empty($data2) || $data2 == true){
        while($row1 =mysqli_fetch_array($data2)) {
            array_push($response, array('jp_num'=>$row1[0], 'business_reg_num'=>$row1[1], 'current_people'=>$current_people[$j], 'field_address'=>$field_address[$j], 'field_name'=>$field_name[$j], 'field_code'=>$field_code[$j],
            'job_name'=>$job_name[$j], 'manager_office_name'=>$manager_office_name[$j], 'jp_title'=>$row1[3], 'jp_contents'=>$row1[4], 'jp_job_cost'=>$row1[5], 'jp_job_tot_people'=>$row1[6], 'jp_job_date'=>$row1[7], 
            'jp_job_start_time'=>$row1[8], 'jp_job_finish_time'=>$row1[9], 'jp_is_urgency'=>$row1[10], 'jp_datetime'=>$row1[11] ));
            $j++;    
           }
    }

    

    
    echo json_encode(array('response'=>$response),JSON_UNESCAPED_UNICODE);
?>
