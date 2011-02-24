<?php
function extractInfo($param) {
$result=array();
while(list($key,$val)=each($param))
{
//echo htmlentities($val)."<br><br>";
$flag = 1;
$param_br=explode('<br/>',$val);

if(preg_match("/([\d]+)<\/a>/",$param_br[0],$match))
{
$train_no=$match[1];
//echo $train_no;
}

if(preg_match("/<b>([\w\-\s\*\(\)]+)<\/b>/",$param_br[0],$match))
{
$train_name=$match[1];
//echo $train_name;
}


if(preg_match("/left([\w\s]+)at/",$param_br[1],$match)) 
{
$prev_station=$match[1];
}

if(preg_match("/is([\w\s]+),/",$param_br[2],$match)) 
{
$next_station=$match[1];
}


if(preg_match_all("/([\d]{2}:[\d]{2})/",$val,$match) && $flag)
{
$time=$match[0];
$est_time_prev=$time[0];
$sch_time_prev=$time[1];
$est_time_next=$time[2];
$sch_time_next=$time[3];

$flag=0;
}

if(isset($train_name)) 
{
$trainData=array('trainName'=>$train_name,'trainNo'=>$train_no,'prevStation'=>$prev_station,'nextStation'=>$next_station,	
	'estimatedTimePrev'=>$est_time_prev,'scheduledTimePrev'=>$sch_time_prev,'estimatedTimeNext'=>$est_time_next,'scheduledTimeNext'=>$sch_time_next);

//print_r($trainData);
array_push($result,$trainData);
unset($train_name);
}

}
return $result;
}


error_reporting(0);
$data=file_get_contents("http://trains.technoparkliving.com");
$h3=explode('</h3>',$data);
$south=$h3[2];
$north=$h3[3];

if(preg_match("/:[\s]+([\d]{2}:[\d]{2})/",$h3[1],$match)) //for updated time
{
$updatedTime=$match[1];
}


$south_details=explode('<hr/>',$south);
$final_south=array("south"=>extractInfo($south_details));

$north_details=explode('<hr/>',$north);
$final_north=array("north"=>extractInfo($north_details));

$time=array("updatedTime"=>$updatedTime);
$data=array("data"=>array($final_south,$final_north,$time));
//$data=array("data"=>array("south"=>extractInfo($south_details),"north"=>extractInfo($north_details),'updatedTime'=>$updatedTime));
echo json_encode($data);
//echo $_GET['stc']." ".$_GET['stn'];

?>
