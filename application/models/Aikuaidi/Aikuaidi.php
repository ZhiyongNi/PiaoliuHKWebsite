<?php

class Aikuaidi
{

    public static function trackExpressInfo ($PackageExpressCompany, 
            $PackageExpressTrackNumber)
    {
        $AppKey = '972c853345b349c2b610be64eb9e4125'; // 请将XXXXXX替换成您在http://kuaidi100.com/app/reg.html申请到的KEY
        $url = 'http://www.aikuaidi.cn/rest/?key=' . $AppKey . '&id=' .
                 $PackageExpressCompany . '&order=' . $PackageExpressTrackNumber .
                 '&show=json&ord=asc';
        
        if (function_exists('curl_init') == 1) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_TIMEOUT, 5);
            $get_content = curl_exec($curl);
            curl_close($curl);
        } else {
            include ("Snoopy.class.php");
            $snoopy = new snoopy();
            $snoopy->referer = 'http://www.google.com/'; // 伪装来源
            $snoopy->fetch($url);
            $get_content = $snoopy->results;
        }
        return $get_content;
    }
}
?>
