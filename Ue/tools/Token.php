<?php
/*
 * Name:apiToken生成
 * Version:1.0
 * Author:阳光男孩
 * Author QQ:51154393
 * Author Url:www.eruyi.cn
*/
namespace Ue\tools;
class Token{
	
	private $key;
	private $header;
	private $iat;
	private $exp;
	public $param;
	
	public function __construct($key){
        $this->key = $key;
		$this->header = ['alg'=>'HS256','typ'=>'JWT'];//头部信息
		$this->iat = time();//签发时间
		$this->exp = time() + (365*24*60*60);//过期时间
    }
	
	public function setExp($time){//设置过期时间，单位/秒
        $this->exp = time()+$time;
		return $this;
    }
	
	public function get($param){//获取token
        $payload = [
            'iat' => $this->iat,  //签发时间
            'exp' => $this->exp,  //过期时间
            'param' => $param, //插入数据
        ];
        $base64header = self::base64UrlEncode(json_encode($this->header, JSON_UNESCAPED_UNICODE));
        $base64payload = self::base64UrlEncode(json_encode($payload, JSON_UNESCAPED_UNICODE));
        return $base64header . '.' . $base64payload . '.' . self::sign($base64header . '.' . $base64payload,$this->header['alg']);
    }
	
	public function verify($data){//验证token
        $tokens = explode('.',$data);
        if(count($tokens) != 3)return false;

        list($base64header, $base64payload, $sign) = $tokens;
        
        $base64decodeheader = json_decode(self::base64UrlDecode($base64header), JSON_OBJECT_AS_ARRAY);//获取jwt算法
        if (empty($base64decodeheader['alg']))return false;
        
        if (self::sign($base64header . '.' . $base64payload, $base64decodeheader['alg']) !== $sign)return false;//签名验证

        $payload = json_decode(self::base64UrlDecode($base64payload), JSON_OBJECT_AS_ARRAY);
		
        //签发时间大于当前服务器时间验证失败
        if (isset($payload['iat']) && $payload['iat'] > time())return false;
		
        //过期时间小宇当前服务器时间验证失败
        if (isset($payload['exp']) && $payload['exp'] < time())return false;
		$this->iat = $payload['iat'];
		$this->exp = $payload['exp'];
        $this->param = $payload['param'];
        return true;
    }
	
	/**
     * base64UrlEncode
     * @param string $data 需要编码的字符串
     * @return string
     */
    private static function base64UrlEncode($data){
        return str_replace('=', '', strtr(base64_encode($data), '+/', '-_'));
    }

    /**
     * base64UrlEncode
     * @param string $data 需要解码的字符串
     * @return bool|string
     */
    private static function base64UrlDecode($data){
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $addlen = 4 - $remainder;
            $data .= str_repeat('=', $addlen);
        }
        return base64_decode(strtr($data, '-_', '+/'));
    }
	
	/**
     * HMACSHA256签名
     * @param string $alg 算法方式
     * @return mixed
     */
    private function sign($data, $alg = 'HS256'){
        $alg_config = array('HS256' => 'sha256');
        return self::base64UrlEncode(hash_hmac($alg_config[$alg],$data,$this->key, true));
    }
}	
?>