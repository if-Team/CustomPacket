<?php

namespace ifteam\CustomPacket\Packet;

use ifteam\CustomPacket\ModPEProtocol as Protocol;

class CustomPacket{
	
	private $type = Protocol::PACKET_UNKNOWN;
	
	public function __construct($rawstring){
		$decoded = $this->splitHeader($rawstring);
		$json = json_decode($decoded[1], true);
		if($json === false){
			$pk = new CustomJSONPacket($json);
		} else if($decoded[0] === Protocol::PACKET_RAW_BASE64){
			$pk = new CustomEncodedPacket($decoded[1]);
		} else {
			$pk = new CustomRawPacket($decoded[1]);
		}
		return $pk;
	}
	
	protected function splitHeader($string){
		return array(mb_substr($string, 0, 1), mb_substr($string, 1));
	}
}
?>