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

if(preg_match("/\| ([\w\-\s\*\(\)]+)/",$val,$match))
{
$train_name=$match[1];
//$train_name=trim(preg_replace('/\|/','',$train_name));
}

if(preg_match("/([\d]+)<\/a>$/",$val,$match))
{
$train_no=$match[1];
}


if(isset($est_time)) 
{
$trainData=array('estimatedTime'=>$est_time,'scheduledTime'=>$sch_time,'trainName'=>$train_name,'trainNo'=>$train_no);
//print_r($trainData);
array_push($result,$trainData);
unset($est_time);
}

}
return $result;
}

$opts = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Accept-language: en\r\n" .
              "Cookie: train-timings\r\n"
  )
);
$context = stream_context_create($opts);
$stc=$_GET['stc'];
$stn=$_GET['stn'];
$url="http://trains.technoparkliving.com/arrivals.php?stc=".$stc."&stn=".$stn;
/*
$fr = fopen($url, 'r', false, $context);
$fw= fopen("traintimings.html","w");
while(!feof($fr))
{
$str=fgets($fr);
fwrite($fw,$str);
}
*/

$data=file_get_contents($url);
//$data=file_get_contents("traintimings.html");
//echo $url;
$h3=explode('</h3>',$data);
$south=$h3[2];
$north=$h3[3];


$south_details=explode('<hr/>',$south);
$final_south= array("south"=>extractInfo($south_details));

$north_details=explode('<hr/>',$north);
$final_north=array("north"=>extractInfo($north_details));

$data=array("data"=>array($final_south,$final_north));
echo json_encode($data);

?>
