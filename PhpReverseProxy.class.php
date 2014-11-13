php
Source Code httpwww.xiumu.orgtechnologyphp-reverse-proxy-class.shtml
class PhpReverseProxy{
  public $publicBaseURL;
  public $outsideHeaders;
  public $XRequestedWith;
  public $sendPost;
  public $port,$host,$ip,$content,$forward_path,$content_type,$user_agent,
    $XFF,$request_method,$IMS,$cacheTime,$cookie,$authorization;
  private $http_code,$lastModified,$version,$resultHeader;
  const chunkSize = 10000;
  function __construct(){
    $this-version=PHP Reverse Proxy (PRP) 1.0;
    $this-port=8080;
    $this-host=127.0.0.1;
    $this-ip=;
    $this-content=;
    $this-forward_path=;
    $this-path=;
    $this-content_type=;
    $this-user_agent=;
    $this-http_code=;
    $this-XFF=;
    $this-request_method=GET;
    $this-IMS=false;
    $this-cacheTime=72000;
    $this-lastModified=gmdate(D, d M Y His,time()-72000). GMT;
    $this-cookie=;
    $this-XRequestedWith = ;
    $this-authorization = ;
  }
  function translateURL($serverName) {
    $this-path=$this-forward_path.$_SERVER['REQUEST_URI'];
    if(IS_SAE)
      return $this-translateServer($serverName).$this-path;
    if($_SERVER['QUERY_STRING']==)
      return $this-translateServer($serverName).$this-path;
    else
    return $this-translateServer($serverName).$this-path..$_SERVER['QUERY_STRING'];
  }
  function translateServer($serverName) {
    $s = empty($_SERVER[HTTPS])  ''
       ($_SERVER[HTTPS] == on)  s
       ;
    $protocol = $this-left(strtolower($_SERVER[SERVER_PROTOCOL]), ).$s;
    if($this-port==) 
      return $protocol..$serverName;
    else
      return $protocol..$serverName..$this-port;
  }
  function left($s1, $s2) {
    return substr($s1, 0, strpos($s1, $s2));
  }
  function preConnect(){
    $this-user_agent=$_SERVER['HTTP_USER_AGENT'];
    $this-request_method=$_SERVER['REQUEST_METHOD'];
    $tempCookie=;
    foreach ($_COOKIE as $i = $value) {
      $tempCookie=$tempCookie. $i=$_COOKIE[$i];;
    }
    $this-cookie=$tempCookie;
    if(empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
      $this-XFF=$_SERVER['REMOTE_ADDR'];
    } else {
      $this-XFF=$_SERVER['HTTP_X_FORWARDED_FOR']., .$_SERVER['REMOTE_ADDR'];
    }
 
  }
  function connect(){
    if(empty($_SERVER['HTTP_IF_MODIFIED_SINCE'])){
      $this-preConnect();
      $ch=curl_init();
      if($this-request_method==POST){
        curl_setopt($ch, CURLOPT_POST,1);
 
        $postData = array();
        $filePost = false;
        $uploadPath = 'uploads';
        if (IS_SAE)
           $uploadPath = SAE_TMP_PATH;
 
        if(count($_FILES)0){
            if(!is_writable($uploadPath)){
                die('You cannot upload to the specified directory, please CHMOD it to 777.');
            }
            foreach($_FILES as $key = $fileArray){ 
                copy($fileArray[tmp_name], $uploadPath . $fileArray[name]);
                $proxyLocation = @ . $uploadPath . $fileArray[name] . ;type= . $fileArray[type];
                $postData = array($key = $proxyLocation);
                $filePost = true;
            }
        }
 
        foreach($_POST as $key = $value){
            if(!is_array($value)){
          $postData[$key] = $value;
            }
            else{
          $postData[$key] = serialize($value);
            }
        }
 
        if(!$filePost){
            $postData = http_build_query($postData);
           $postString = ;
           $firstLoop = true;
           foreach($postData as $key = $value){
            $parameterItem = urlencode($key).=.urlencode($value);
            if($firstLoop){
          $postString .=  $parameterItem;
            }
            else{
          $postString .=  &.$parameterItem;
            }
            $firstLoop = false; 
           }
           $postData = $postString;
        }
 
        echo print_r($postData);
 
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, Mozilla4.0 (compatible;));
        $this-sendPost =  $postData;
        var_dump(file_exists(str_replace('@','',$postData['imgfile'])));exit;
        curl_setopt($ch, CURLOPT_POSTFIELDS,$postData);
        curl_setopt($ch, CURLOPT_POSTFIELDS,file_get_contents($proxyLocation));
        curl_setopt($ch, CURLOPT_POSTFIELDS,file_get_contents(phpinput));
      }
 
      gets rid of mulitple  in URL
      $translateURL =  $this-translateURL(($this-ip)$this-ip$this-host);
      if(substr_count($translateURL, )1){
          $firstPos = strpos($translateURL, , 0);
          $secondPos = strpos($translateURL, , $firstPos + 1);
          $translateURL = substr($translateURL, 0, $secondPos);
      }
 
      curl_setopt($ch,CURLOPT_URL,$translateURL);
 
      $proxyHeaders = array(
          X-Forwarded-For .$this-XFF,
          User-Agent .$this-user_agent,
          Host .$this-host
      );
 
      if(strlen($this-XRequestedWith)1){
          $proxyHeaders[] = X-Requested-With .$this-XRequestedWith;
          echo print_r($proxyHeaders);
      }
 
      curl_setopt($ch,CURLOPT_HTTPHEADER, $proxyHeaders);
 
      if($this-cookie!=){
        curl_setopt($ch,CURLOPT_COOKIE,$this-cookie);
      }
      curl_setopt($ch,CURLOPT_FOLLOWLOCATION,false); 
      curl_setopt($ch,CURLOPT_AUTOREFERER,true); 
      curl_setopt($ch,CURLOPT_HEADER,true);
      curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
 
      $output=curl_exec($ch);
      $info = curl_getinfo( $ch );
      curl_close($ch);
      $this-postConnect($info,$output);
    }else {
      $this-lastModified=$_SERVER['HTTP_IF_MODIFIED_SINCE'];
      $this-IMS=true;
    }
  }
  function postConnect($info,$output){
    $this-content_type=$info[content_type];
    $this-http_code=$info['http_code'];
    var_dump($info);exit;
    if(!empty($info['last_modified'])){
      $this-lastModified=$info['last_modified'];
    }
    $this-resultHeader=substr($output,0,$info['header_size']);
    $content = substr($output,$info['header_size']);
 
    if($this-http_code=='200'){
      $this-content=$content;
    }elseif( ($this-http_code=='302'  $this-http_code=='301') && isset($info['redirect_url'])){
      $redirect_url = str_replace($this-host,$_SERVER['HTTP_HOST'],$info['redirect_url']);
      if (IS_SAE)
         $redirect_url = str_replace('httpfetchurl.sae.sina.com.cn','',$info['redirect_url']);
      header(Location $redirect_url);
      exit;
    }elseif($this-http_code=='404'){
      header(HTTP1.1 404 Not Found);
      exit(HTTP1.1 404 Not Found);
    }elseif($this-http_code=='500'){
      header('HTTP1.1 500 Internal Server Error');
      exit(HTTP1.1 500 Internal Server Error);
    }else{
      exit(HTTP1.1 .$this-http_code. Internal Server Error);
    }
  }
 
  function output(){
    $currentTimeString=gmdate(D, d M Y His,time());
    $expiredTime=gmdate(D, d M Y His,(time()+$this-cacheTime));
 
    $doOriginalHeaders = true;
    if($doOriginalHeaders){
        if($this-IMS){
          header(HTTP1.1 304 Not Modified);
          header(Date Wed, $currentTimeString GMT);
          header(Last-Modified $this-lastModified);
          header(Server $this-version);
        }else{
 
          header(HTTP1.1 200 OK);
          header(Date Wed, $currentTimeString GMT);
          header(Content-Type .$this-content_type);
          header(Last-Modified $this-lastModified);
          header(Cache-Control max-age=$this-cacheTime);
          header(Expires $expiredTime GMT);
          header(Server $this-version);
          preg_match(Set-Cookie[^n]i,$this-resultHeader,$result);
          foreach($result as $i=$value){
            header($result[$i]);
          }
          preg_match(Content-Encoding[^n]i,$this-resultHeader,$result);
          foreach($result as $i=$value){
            header($result[$i]);
          }
          preg_match(Transfer-Encoding[^n]i,$this-resultHeader,$result);
          foreach($result as $i=$value){
            header($result[$i]);
          }
          echo($this-content);
          
          if(stristr($this-content, error)){
        echo print_r($this-sendPost);
          }
          
        }
    }
    else{
        $headerString = $this-resultHeader; string 
        $headerArray = explode(n, $headerString);
        foreach($headerArray as $privHeader){
      header($privHeader);
        }
 
        if(stristr($headerString, Transfer-encoding chunked)){
      flush();
      ob_flush();
      $i = 0;
      $maxLen = strlen($this-content);
 
      while($i  $maxLen){
          $endChar = $i + selfchunkSize;
          if($endChar = $maxLen){
        $endChar = $maxLen - 1;
          }
          $chunk = substr($this-content, $i, $endChar);
          $this-dump_chunk($chunk);
          flush();
          ob_flush();
          $i = $i + $endChar;
      }
        }
        else{
       echo($this-content);
        }
 
        echo header .print_r($headerArray);
        header($this-resultHeader);
    }
 
  }
 
 
  function dump_chunk($chunk) {
      echo sprintf(%xrn, strlen($chunk));
      echo $chunk;
      echo rn;
  }
 
 
  function getOutsideHeaders(){
      $headers = array();
      foreach ($_SERVER as $name = $value){ 
    if (substr($name, 0, 5) == 'HTTP_') { 
        $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5))))); 
        $headers[$name] = $value; 
    }elseif ($name == CONTENT_TYPE) { 
        $headers[Content-Type] = $value; 
    }elseif ($name == CONTENT_LENGTH) { 
        $headers[Content-Length] = $value; 
    }elseif(stristr($name, X-Requested-With)) { 
        $headers[X-Requested-With] = $value;
        $this-XRequestedWith = $value;
    }
      } 
 
      echo print_r($headers);
 
      $this-outsideHeaders = $headers;
      return $headers;
  }  
 
}
