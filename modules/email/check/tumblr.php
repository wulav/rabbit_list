<?php
include '../../../config.php';
access_by_cookie($config,$_COOKIE);
$email=$_GET['email'];
function getbearer()
{
$url="https://www.tumblr.com/register/bird?";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch,CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_2_0);
$output_data=curl_exec($ch);
curl_close($ch);
preg_match_all('/"apiFetchStore":{"API_TOKEN":"(.*)","extraHeaders":"/',$output_data,$result);
return $result[1][0];
}

function check($email)
{
$bearer=getbearer();
$url="https://www.tumblr.com/api/v2/user/validate";
$headers = array(
    "Host: www.tumblr.com",
    'Content-Type: application/json; charset=utf8',
    "Authorization: Bearer $bearer"
);
$post="{\"email\":\"$email\"}";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch,CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_2_0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$output_data=curl_exec($ch);
curl_close($ch);
foreach(json_decode($output_data) as $a => $b)
{
    if($a=='response')
    {
foreach($b as $c => $d)
{
    if($c=='user_errors')
{
    foreach($d as $e)
    {
    foreach($e as $f => $g)
    {
        if($f=='message')
        {
if($g=='User already exists')
{
    $site="tumblr.com";
    echo '[{"from_id":"'.$email.'","root_id":"registed:'.$site.'","root_label":"registed:'.$site.'","type":"registed","imageurl":"/img/icon/registed.png","title":"该邮箱可能注册 '.$site.',通常意味着邮箱主人使用境外社交媒体","raw_data":"","edge_color":"orange","edge_label":"注册枚举"}]';
    exit ;
}            
        }
    }   
    }
}
}
    }
}
echo '[{}]';
}
check($email);
// getbearer();
?>