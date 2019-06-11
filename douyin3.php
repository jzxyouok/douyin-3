<?php
header("Content-Type:text/html;charset=utf-8");
$url_g=@$_GET['url'];//抖音视频地址
$api="https://aweme.snssdk.com/aweme/v1/aweme/detail/?origin_type=link&retry_type=no_retry&iid=75054249122&device_id=68246587723&ac=wifi&channel=aweGW&aid=1128&app_name=aweme&version_code=660&version_name=6.6.0&device_platform=android&ssmix=a&device_type=DUK-AL20&device_brand=HUAWEI&language=zh&os_api=22&os_version=5.1.1&uuid=861697659272836&openudid=1030044281966835&manifest_version_code=660&resolution=720*1280&dpi=192&update_version_code=6602&_rticket=1560240474014&mcc_mnc=46000&js_sdk_version=1.16.0.0&ts=1560240473&aweme_id=";
$user_agent="Mozilla/5.0 (Linux; Android 8.0.0; MI 6 Build/OPR1.170623.027; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/62.0.3202.84 Mobile Safari/537.36";
function getLinkFromDouyinShareText($shareOrUrl){
    $url="http".explode("http",$shareOrUrl)[1];
    $url=explode("复制此链接，",$url)[0];
    return $url;
}
function getAwemeId($link,$UA){
    $context=stream_context_create(array('http'=>array('header'=>"User-Agent:".$UA)));
    $html_text= file_get_contents($link,0,$context);
    $str=explode("itemId: \"",$html_text)[1];
    $str=explode("\",",$str)[0];
    return $str;
}
function doCurlGetRequest($url,$headers,$timeout = 5){
    if($url == '' || $timeout <=0){
        return false;
    }
    $con = curl_init((string)$url);
    curl_setopt($con, CURLOPT_HEADER, false);
    curl_setopt($con, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($con,CURLOPT_HTTPHEADER,$headers);
    curl_setopt($con, CURLOPT_TIMEOUT,(int)$timeout);
    return curl_exec($con);
}
function getVideoData($api,$cookie,$awemeId){
    $header=array("Accept-Encoding: utf-8",
                "Cookie: ".$cookie,
                "User-Agent: okhttp/3.10.0.1"
				);
	$time=time();
    $_rticket=$time.'139';
    $data=json_decode(doCurlGetRequest($api.$awemeId."&ts=$time"."&_rticket=$_rticket",$header),true);
	$detail=@$data['aweme_detail'];
    $info=@$detail['share_info'];//视频分享信息
    $user_name=@$detail['author']['nickname'];//作者昵称
	$shortId=@$detail['author']['short_id'];//作者抖音号
    $user_headImg=@$detail['author']['avatar_medium']['url_list'][0];//作者头像
    $image=@$detail['video']['origin_cover']['url_list'][0];//封面图片
    $urls=@$detail['video']['play_addr']['url_list'];//无水印地址
    $music_urls=@$detail['music']['play_url']['url_list'];//音乐地址
	$userId=@$detail['author_user_id'];//用户userId
	$dynamic_cover=@$detail['video']['dynamic_cover']['url_list'][0];//封面动态图地址
	$longVideo=@$detail['long_video'][0]['video']['bit_rate'];//长视频
	
	if(!$longVideo){
		$longVideo=[];
	}
	
    $douyin=[
        'status'=>true,
        'nickname'=>$user_name,
		'shortId'=>$shortId,
		'userId'=>$userId,
        'awemeId'=>$awemeId,
        'headImage'=>$user_headImg,
        'image'=>$image,
		'dynamic_cover'=>$dynamic_cover,
        'urls'=>$urls,
		'long_video'=>$longVideo,
        'music_urls'=>$music_urls,
        'info'=>$info
    ];
    return json_encode($douyin);
}
if(strstr($url_g,'http://v.douyin.com/')) {
    $url_g = getLinkFromDouyinShareText($url_g);
    $awemeId=getAwemeId($url_g,$user_agent);
    echo getVideoData($api,$cookie,$awemeId);
}else{
    echo json_encode(["status"=>false,"message"=>"地址无效"]);
}