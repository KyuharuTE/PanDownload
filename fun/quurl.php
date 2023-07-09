<?php
// 蓝奏云
function lanzou($url){
    $UserAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36';
    $pwd = isset($_GET['pwd']) ? $_GET['pwd'] : "";
    $type = isset($_GET['type']) ? $_GET['type'] : "";
    if (empty($url)) {
    	    return '请输入链接';
    	    exit;
    }
    $url='https://www.lanzoue.com/'.explode('.com/',$url)['1'];
    $softInfo = MloocCurlGet($url);
    if (strstr($softInfo, "文件取消分享了") != false) {
    	 return '文件取消分享了';
    	 exit;
    }
    preg_match('~style="font-size: 30px;text-align: center;padding: 56px 0px 20px 0px;">(.*?)</div>~', $softInfo, $softName);
    if(!isset($softName[1])) {
    	preg_match('~<div class="n_box_3fn".*?>(.*?)</div>~', $softInfo, $softName);
    }
    preg_match('~<div class="n_filesize".*?>大小：(.*?)</div>~', $softInfo, $softFilesize);
    if(!isset($softFilesize[1])) {
    	preg_match('~<span class="p7">文件大小：</span>(.*?)<br>~', $softInfo, $softFilesize);
    }
    if(!isset($softName[1])) {
    	preg_match('~var filename = \'(.*?)\';~', $softInfo, $softName);
    }
    if(!isset($softName[1])) {
    	preg_match('~div class="b"><span>(.*?)</span></div>~', $softInfo, $softName);
    }
    if(strstr($softInfo, "function down_p(){") != false) {
    	if(empty($pwd)) {
    		return '请输入分享密码，暂不支持解析含有密码的分享链接';
    		exit;
    	}
    	preg_match_all("~action=(.*?)&sign=(.*?)&p='\+(.*?),~", $softInfo, $segment);
    	$post_data = array(
    			"action" => 'downprocess',
    			"sign" => $segment[2][1],
    			"p" => $pwd
    		);
    	$softInfo = MloocCurlPost($post_data, "https://www.lanzoue.com/ajaxm.php", $url);
    	$softName[1] = json_decode($softInfo,JSON_UNESCAPED_UNICODE)['inf'];
    } else {
    	preg_match("~\n<iframe.*?name=\"[\s\S]*?\"\ssrc=\"\/(.*?)\"~", $softInfo, $link);
    	if(empty($link[1])) {
    		preg_match("~<iframe.*?name=\"[\s\S]*?\"\ssrc=\"\/(.*?)\"~", $softInfo, $link);
    	}
    	$ifurl = "https://www.lanzoue.com/" . $link[1];
    	$softInfo = MloocCurlGet($ifurl);
    	preg_match_all("~sign':'(.*?)'~", $softInfo, $segment);
    	$post_data = array(
    			"action" => 'downprocess',
    			"signs"=>"?ctdf",
    			"sign" => $segment[1][0],
    		);
    	$softInfo = MloocCurlPost($post_data, "https://www.lanzoue.com/ajaxm.php", $ifurl);
    }
    $softInfo = json_decode($softInfo, true);
    if ($softInfo['zt'] != 1) {
    	return $softInfo['inf'];
    	exit;
    }
    $downUrl1 = $softInfo['dom'] . '/file/' . $softInfo['url'];
    $downUrl2 = MloocCurlHead($downUrl1,"https://developer.lanzoug.com",$UserAgent,"down_ip=1; expires=Sat, 16-Nov-2019 11:42:54 GMT; path=/; domain=.baidupan.com");
    if($downUrl2 == "") {
    	$downUrl = $downUrl1;
    } else {
    	$downUrl = $downUrl2;
    }
    if ($type != "down") {
    	return $downUrl;
    	exit;
    } else {
    	header("Location:$downUrl");
    	die;
    }
}
function MloocCurlGetDownUrl($url) {
	$header = get_headers($url,1);
	if(isset($header['Location'])) {
		return $header['Location'];
	}
	return "";
}
function MloocCurlGet($url = '', $UserAgent = '') {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
	if ($UserAgent != "") {
		curl_setopt($curl, CURLOPT_USERAGENT, $UserAgent);
	}
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.Rand_IP(), 'CLIENT-IP:'.Rand_IP()));
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec($curl);
	curl_close($curl);
	return $response;
}
function MloocCurlPost($post_data = '', $url = '', $ifurl = '', $UserAgent = '') {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_USERAGENT, $UserAgent);
	if ($ifurl != '') {
		curl_setopt($curl, CURLOPT_REFERER, $ifurl);
	}
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.Rand_IP(), 'CLIENT-IP:'.Rand_IP()));
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
	$response = curl_exec($curl);
	curl_close($curl);
	return $response;
}
function MloocCurlHead($url,$guise,$UserAgent,$cookie) {
	$headers = array(
		'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
		'Accept-Encoding: gzip, deflate',
		'Accept-Language: zh-CN,zh;q=0.9',
		'Cache-Control: no-cache',
		'Connection: keep-alive',
		'Pragma: no-cache',
		'Upgrade-Insecure-Requests: 1',
		'User-Agent: '.$UserAgent
	);
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HTTPHEADER,$headers);
	curl_setopt($curl, CURLOPT_REFERER, $guise);
	curl_setopt($curl, CURLOPT_COOKIE , $cookie);
	curl_setopt($curl, CURLOPT_USERAGENT, $UserAgent);
	curl_setopt($curl, CURLOPT_NOBODY, 0);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLINFO_HEADER_OUT, TRUE);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_TIMEOUT, 10);
	$data = curl_exec($curl);
	$url=curl_getinfo($curl);
	curl_close($curl);
	return $url["redirect_url"];
}
function Rand_IP() {
	$ip2id = round(rand(600000, 2550000) / 10000);
	$ip3id = round(rand(600000, 2550000) / 10000);
	$ip4id = round(rand(600000, 2550000) / 10000);
	$arr_1 = array("218","218","66","66","218","218","60","60","202","204","66","66","66","59","61","60","222","221","66","59","60","60","66","218","218","62","63","64","66","66","122","211");
	$randarr= mt_rand(0,count($arr_1)-1);
	$ip1id = $arr_1[$randarr];
	return $ip1id.".".$ip2id.".".$ip3id.".".$ip4id;
}
// 蓝奏云

// 123pan
class d123pan{
    private $UserAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36';
    protected $cachepath='cache/';//缓存目录
    public $cache_time= 0;//缓存时间 0 为不缓存
    public function getUrl($url,$pwd=''){
        $return =array('status'=>0,'info'=>'');
        if(empty($url)){$return['info']= '请输入URL';return $return;}
        if($this->str_exists($url,'http')){
            $urlarr = explode('/',str_replace('.html','',$url));
            $shareKey = $urlarr[count($urlarr)-1];
        }else{
            $shareKey=$url;
        }
        if($this->cache_time>0){
            $cachekey = $shareKey.$pwd;
            $cacheresult = $this->cache($cachekey);
            if($cacheresult && $cacheresult['expires_time']>time())return $cacheresult['data'];
        }
        if(empty($pwd)){
            $url = 'https://www.123pan.com/s/'.$shareKey.'.html';
            $softInfo = $this->curlget($url);
            preg_match("~window.g_initialProps(.*?)};~", $softInfo, $segment);
            $jsonstr =  trim(trim($segment[1]),'=')."}";
            $jsonarr = json_decode($jsonstr,1);
            if(empty($jsonarr)){$return['info']= '解析错误';return $return;}
            if($jsonarr['res']['data']['HasPwd']=='false'){$return['info']= '请输入提取码';return $return;}            
            $softInfo = $jsonarr['reslist'];
        }else{
            $url = 'https://www.123pan.com/b/api/share/get?limit=100&next=1&orderBy=share_id&orderDirection=desc&shareKey='.$shareKey.'&SharePwd='.$pwd.'&ParentFileId=0&Page=1';
            $softInfo = json_decode($this->curlget($url),true);
            if($softInfo['code']>0){
                $return['info']= $softInfo['message'];return $return;
            }
        }
        $url = 'https://www.123pan.com/b/api/share/download/info';
        $info = $softInfo['data']['InfoList'][0];
        $param=array(
            'Etag'=> $info['Etag'],
            'FileID'=>  $info['FileId'],
            'S3keyFlag'=> $info['S3KeyFlag'],
            'ShareKey'=> $shareKey,
            'Size'=> $info['Size'],
        );
        $softInfo = json_decode($this->curlget($url,$param,'POST'),true);
        if($softInfo['code']>0){
            $return['info']= $softInfo['message'];return $return;
        }
        $downUrl = $softInfo['data']['DownloadURL'];
        if(empty($downUrl)){$return['info']= '获取下载地址失败';return $return;}
        $return['status']=1;
        $return['info']=$downUrl;
        if($this->cache_time>0){
            $cacheresult=array();
            $cacheresult['data']=$return;
            $cacheresult['expires_time']=time()+$this->cache_time;
            $this->cache($cachekey,$cacheresult);
        }
        return $return;
    }
    public function cache($key,$value='',$time=''){
        if(is_array($key))$key=md5(implode('',$key));
        $filename=$this->cachepath.$key.'.cache';
        if(empty($value)){
            $data= @file_get_contents($filename);$this->clearcache();
            return json_decode($data,1);
        }else{
            if(!is_array($value))$value=array($value);
            file_put_contents($filename,json_encode($value));
        }
    }
    //清空所有缓存
    public function clearcache(){
       $cachepath=$this->cachepath;
       $date=date('Y-m-d');$cachename='cachetime'.$date.'.c';
       if(file_exists($cachepath.$cachename))return false;
       foreach(scandir($cachepath) as $fn) {
    	if(strpos($cachename,'.c')>0)unlink($cachepath.$fn);
       }file_put_contents($cachepath.$cachename,'1');
       return true;
    }
    /**
     * CURL发送HTTP请求
     * @param  string $url    请求URL
     * @param  array  $params 请求参数
     * @param  string $method 请求方法GET/POST
     * @param  $header 头信息
     * @param  $multi  是否支付附件
     * @param  $debug  是否输出错误
     * @param  $optsother 附件项
     * @return array  $data   响应数据
     */
    private function curlget($url, $params='', $method = 'GET', $header = array(), $UserAgent = false,$debug=false,$optsother='') {
        if(empty($UserAgent))$UserAgent=$this->UserAgent;
    	$opts = array(CURLOPT_TIMEOUT => 10,CURLOPT_RETURNTRANSFER=> 1,CURLOPT_SSL_VERIFYPEER=> false,CURLOPT_SSL_VERIFYHOST=> false,CURLOPT_HTTPHEADER => $header,CURLOPT_USERAGENT=>$UserAgent);		
    	switch (strtoupper($method)) {/* 根据请求类型设置特定参数 */
    		case 'GET':$opts[CURLOPT_URL] = $params?$url.'?'.http_build_query($params):$url;break;
    		case 'POST':$params = http_build_query($params);//判断是否传输文件
        	$opts[CURLOPT_URL] = $url;$opts[CURLOPT_POST] = 1;$opts[CURLOPT_POSTFIELDS] = $params;break;			
    		default:if($debug)echo ('不支持的请求方式！');break;
    	}$ch = curl_init();if($optsother && is_array($optsother))$opts=$opts+$optsother;curl_setopt_array($ch, $opts);$data = curl_exec($ch);$error = curl_error($ch);curl_close($ch);/* 初始化并执行curl请求 */
    	if($error && $debug){echo ('请求发生错误:'.$error);}
    	return $data;
    }//检测字符串中是否存在
    private function str_exists($haystack, $needle){
    	return !(strpos(''.$haystack, ''.$needle) === FALSE);
    }
}
//123pan
?>