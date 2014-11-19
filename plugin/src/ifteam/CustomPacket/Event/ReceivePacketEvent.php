<?php

namespace ifteam\CustomPacket\event;

use pocketmine\plugin\PluginBase;
use ifteam\CustomPacket\Packet\CustomPacket;

class ReceivePacketEvent extends CustomPacketEvent{

	private $data;
	
	public function __construct($packet, $ip, $port) {
		$packet->decode();
		parent::__construct ($packet);
	}
}

?>