<?php

namespace ifteam\CustomPacket\event;

use pocketmine\plugin\PluginBase;

class ReceivePacketEvent extends CustomPacketEvent{
	public function __construct($packet, $ip, $port) {
		parent::__construct ( $packet, $ip, $port );
	}
}

?>