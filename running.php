<?php
function extractInfo($param) {
$result=array();
while(list($key,$val)=each($param))
{
//echo htmlentities($val)."<br><br>";
if(preg_match_all("/([\d]{2}:[\d]{2})/",$val,$match))
{
$time=$match[0];
$est_time=$time[0];
$sch_time=$time[1];
}

if(preg_match("/([\w\-\s\*\(\)]+)<\/a>/",$val,$match))
{
$train_name=$match[1];
}


if(isset($est_time)) 
{
$trainData=array('estimatedTime'=>$est_time,'scheduledTime'=>$sch_time,'stationName'=>$train_name);
//print_r($trainData);
array_push($result,$trainData);
unset($est_time);
}

}
return $result;
}

//error_reporting(0);
$url="http://trains.technoparkliving.com/running.php?tno=".$_GET['tno']."&tname=".$_GET['tname'];
$data=file_get_contents($url);
$h3=explode('</h3>',$data);
$list=$h3[1];
$hr_split=explode('<hr/>',$list);
$data=array("data"=>extractInfo($hr_split));

echo json_encode($data);
?>
