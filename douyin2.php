<?php
header('Content-type: application/json');
if (!empty($_GET['url'])) {
    $url = $_GET['url'];
    $str = GET($url, 1);
    echo $str;
    preg_match("/video_id=(.*?)&/i", $str, $arr);
    // echo json_encode(array('result' => $arr), JSON_UNESCAPED_SLASHES);
    // if (count($arr) >= 1) {
    //     echo json_encode(array('result' => $arr), JSON_UNESCAPED_SLASHES);
    //     $str = GET("https://aweme.snssdk.com/aweme/v1/play/?video_id=".$arr[1]."&line=0", 0);
    //     preg_match('#<a href="(.*?)">#', $str, $arr2);
    //     if (count($arr2) >= 1) {
    //         echo json_encode(array('result' => $arr2), JSON_UNESCAPED_SLASHES);
    //         $arr3 = explode("//", $arr2[1]);
    //         if (!empty($arr3)) {
    //             //header("content-type:video/mp4");
    //             //header("Location: "."https://".$arr3[1]);
    //             if (!empty($_GET['way']) && $_GET['way'] == "txt") {
    //                 exit("https://".$arr3[1]);
    //             }
    //             elseif(!empty($_GET['way']) && $_GET['way'] == "json") { 
    //                 $aray = ['code' =>200, 'msg' =>'success', 'url' =>"https://".$arr3[1]];
    //                 exit(json_encode($aray, false));
    //             } else { 
    //                 header("Location: "."https://".$arr3[1]);
    //             }

    //         }

    //     }
    // }

} else {
    echo "lhr0321";
}

function Get($url, $foll = 0) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);       
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["user-agent: Mozilla/5.0 (iPhone; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5376e Safari/8536.25"]); 
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $foll); 
    $output = curl_exec($ch); 
    curl_close($ch);
    return $output; 
}
?>