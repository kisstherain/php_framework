<?php
class Util{
    private static $_instance = NULL;
    private function __clone(){}
    private function __construct(){}
    public static function init(){
        if(null !== self::$_instance){
            return self::$_instance;
        }
        self::$_instance = new self;
        return self::$_instance;
    }
    public function addStyle($css){
        $path = core\Conf::init()->getConf("CSSPATH");
        $version = core\Conf::init()->getEnviroment() === 'production'
            ?core\Conf::init()->getConf("CSSVERSION")
            :time();
        if(is_array($css)){
            foreach ($css as $c) {
                $_path = $this->isRealLink($c)?"":$path;
                echo '<link rel="stylesheet" href="'.$_path.$c."?_t=".$version.'" media="all">';
            }
        }else{
            $_path = $this->isRealLink($css)?"":$path;
            echo '<link rel="stylesheet" href="'.$_path.$css."?_t=".$version.'" media="all">';
        }
    }
    public function addScript($js){
        $path = core\Conf::init()->getConf("JSPATH");
        $version = core\Conf::init()->getEnviroment() === 'production'
            ?core\Conf::init()->getConf("JSVERSION")
            :time();
        if(is_array($js)){
            foreach ($js as $j) {
                $_path = $this->isRealLink($j)?"":$path;
                echo '<script src="'.$_path.$j."?_t=".$version.'"></script>';
            }
        }else{
            $_path = $this->isRealLink($js)?"":$path;
            echo '<script src="'.$_path.$js."?_t=".$version.'"></script>';
        }
    }
    public function redirect($url){
        return header("Location:".$url);
    }
    public function getEnviroment(){
        return Config::init()->getEnviroment();
    }
    public function isRealLink($url){
        $urlInfo = parse_url($url);
        return isset($urlInfo['scheme']) && !empty($urlInfo['scheme']);
    }
    public static $code_map = array(
        1001 =>"success"
    );
    public static function jsonReturn($code = 1001, $result = null,$msg = ""){
        $msg = !empty($msg)?$msg:(isset(self::$code_map[$code])?self::$code_map[$code]:"");
        $return = array( 'status'=>array('code' => $code, 'msg' => $msg),
            'result'=>$result, );
        echo json_encode($return,1);
        exit;
    }
    public static function getFileSize($path,$type = false){
        if(!is_file($path)){
            return 0;
        }
        $size = filesize($path);
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        if($type){
            if(!in_array($type,$units)){
                return 0;
            }
            for ($i = 0; $i < 4 && $units[$i]!=$type; $i++){
                $size /= 1024;
            }
            return round($size, 2);
        }else{
            for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
            return round($size, 2).$units[$i];
        }
    }
    public static function deldir($dir){
        if(!is_dir($dir)){
            return false;
        }
        $dirFiles = scandir($dir);
        foreach ($dirFiles as $v) {
            if($v != "." && $v != ".."){
                $path = $dir."/".$v;
                if(!is_dir($path)){
                    unlink($path);
                }else{
                    Util::deldir($path);
                }
            }
        }
        return rmdir($dir);
    }
    public static function curl_request($url,$post='',$cookie='', $returnCookie=0){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_REFERER, "http://mobile.mogujie.org");
        if($post) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
        }
        if($cookie) {
            curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        }
        curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        if (curl_errno($curl)) {
            return curl_error($curl);
        }
        curl_close($curl);
        if($returnCookie){
            list($header, $body) = explode("\r\n\r\n", $data, 2);
            preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
            $info['cookie']  = substr($matches[1][0], 1);
            $info['content'] = $body;
            return $info;
        }else{
            return $data;
        }
    }
}
