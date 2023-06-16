<?php
/**
 * Name:Xml输出类类
 * Version:1.0
 * Author:阳光男孩
 * Author QQ:51154393
 * Author Url:www.uephp.com
**/
namespace Ue\tools;
class Xml{
    private $version  = '1.0';
    private $encoding  = 'UTF-8';
    private $xml    = null;
	
    function __construct(){
        $this->xml = new \SimpleXMLElement("<Uephp></Uephp>");
    }
	
    function toXml($data){
		foreach ($data as $key => $value) {
			if (is_array($value)) {
				$subnode = $this->xml->addChild($key);
				foreach ($value as $subkey => $subvalue) {
					$subnode->addChild($subkey, $subvalue);
				}
			} else {
				$this->xml->addChild($key, $value);
			}
		}
		header("Content-type:text/xml");//输出xml头信息
		return $this->xml->asXML();
    }
}
?>