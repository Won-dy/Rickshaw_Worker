<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<?php
 header('Content-Type: application/json; charset=utf-8');

    $con = mysqli_connect("localhost", "rickshaw", "capstone1!", "rickshaw");
    $response = array();
    $response1 = array();
    $response2 = array();
    $response3 = array();
    $response4 = array();
    
// post값 받아와서 직종으로 검색하기 예시
//    $a =$_POST["jp_num"];
//$a =1;
//$result = mysqli_query($con, "SELECT * FROM JobPosting WHERE jp_num = $a");

$selectQuery ="SELECT * FROM JobPosting";

$key = $_POST["key"];

//서치뷰 검색할 때
if($key=='0')
{
$search_text = $_POST["search_text"];
$selectQuery = $selectQuery." LEFT JOIN Manager ON JobPosting.business_reg_num = Manager.business_reg_num WHERE jp_title LIKE '%$search_text%' OR manager_office_name LIKE '%$search_text%'";
									
}//서치뷰 검색할 때 끝
else if($key=='1')
{

$business_reg_num_MY = $_POST["business_reg_num_MY"];
$local_sido =$_POST["local_sido"];
$local_sigugun =$_POST["local_sigugun"];
$job_code1 =$_POST["jobname0"];
$job_code2 =$_POST["jobname1"];
$job_code3=$_POST["jobname2"];
$index=0;
echo $local_sido;
echo $local_sigugun;
//전체일 땐 business_reg_num_MY =0;
//전체일 땐 local_sido = 전체, local_sigugun="0"
//전체일 땐 job_code123 = 0
//디폴트 0서울종로구000


//전체이거나 내 구인글
if($business_reg_num_MY=="0")
{
}
else
{
if($index==0)
{
$selectQuery = $selectQuery. " WHERE";
$index++;
}
$selectQuery =$selectQuery." business_reg_num = $business_reg_num_MY";
}


//전체이거나 해당 로컬 코드
if($local_sido==="전체")
{
}
else
{
$result = mysqli_query($con, "SELECT * FROM Local WHERE local_sido = '$local_sido' AND local_sigugun = '$local_sigugun'");
$row = mysqli_fetch_assoc($result);

$selLocal_code = $row["local_code"];

	if($index==0)
	{
	$selectQuery = $selectQuery. " WHERE";
	$index++;
	}
	else
	{
	$selectQuery = $selectQuery." AND";
	}
$selectQuery = $selectQuery. " local_code =$selLocal_code";

}

//잡코드 전체이거나 jobcode1만이거나 jobcode1,2만이거나 jobcode1,2,3이거나
if($job_code1 !=="0")
{
if($index==0)
{
$selectQuery = $selectQuery. " WHERE";
$index++;
}
else
{
$selectQuery = $selectQuery." AND";
}
$selectQuery = $selectQuery. " job_code =$job_code1";
if($job_code2 !=="0")
{
$selectQuery = $selectQuery." OR job_code=$job_code2";
if($job_code3 !=="0")
{
$selectQuery = $selectQuery." OR job_code =$job_code3";
}
}
}
echo $selectQuery;
}//search뷰 검색 아닐때 끝else if($key=='1')문 끝

else if($key=='2')
{

$field_code_MY = $_POST["search_text"];
$result = mysqli_query($con, "SELECT * FROM Field WHERE Field.field_code = '$field_code_MY'");

$row = mysqli_fetch_assoc($result);

$jp_num = $row["jp_num"];

$selectQuery ="SELECT * FROM JobPosting WHERE JobPosting.jp_num = '$jp_num'";
}

$selectQuery = $selectQuery." ORDER BY jp_job_date";
$selectJobPosting =  mysqli_query($con, $selectQuery);

 while($row = mysqli_fetch_assoc($selectJobPosting)){  

  $response[] = $row;
  
  }

//타 테이블 select 재료 준비
for($i=0; $i<count($response);$i++)
{
//jp_num(field참조), business_reg_num(Manager참조), job_code(job테이블 참조), jp_num(Apply테이블 참조)
$seljp_num[] = $response[$i]["jp_num"];
$selbusiness_reg_num[] = $response[$i]["business_reg_num"];
$seljob_code[] = $response[$i]["job_code"];

//seljp_num들 갖다가 Field값 전체 가져오기 (검색 결과가 한 행뿐이라 2차원배열로 가능)
$result = mysqli_query($con,"SELECT * FROM Field WHERE jp_num = $seljp_num[$i]");
  while($row = mysqli_fetch_assoc($result)){  

$response1[] = $row;

  }

//selbusiness_reg_num들 갖다가 Manager값 전체 가져오기 (검색 결과가 한 행뿐이라 2차원배열로 가능)
$result = mysqli_query($con,"SELECT * FROM Manager WHERE business_reg_num = '$selbusiness_reg_num[$i]'");
  while($row = mysqli_fetch_assoc($result)){  

$response2[] = $row;


}

//seljob_code들 갖다가 Job값 전체 가져오기 (검색 결과가 한 행뿐이라 2차원배열로 가능)
$result = mysqli_query($con,"SELECT * FROM Job WHERE job_code = '$seljob_code[$i]'");
  while($row = mysqli_fetch_assoc($result)){  

$response3[] = $row;


}

//seljp_num들 갖다가 Apply값 전체 가져오기 ()
$result = mysqli_query($con,"SELECT COUNT(*) FROM Apply WHERE jp_num = '$seljp_num[$i]' AND apply_is_picked ='1'");
  while($row = mysqli_fetch_assoc($result)){  

$response4[] = $row;

  }

}//for문 끝

    echo json_encode($response,JSON_UNESCAPED_UNICODE);
   echo json_encode($response1,JSON_UNESCAPED_UNICODE);
   echo json_encode($response2,JSON_UNESCAPED_UNICODE);
   echo json_encode($response3,JSON_UNESCAPED_UNICODE);
   echo json_encode($response4,JSON_UNESCAPED_UNICODE);



?>