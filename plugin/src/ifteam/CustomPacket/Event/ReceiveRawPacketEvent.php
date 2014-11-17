<?php

namespace ifteam\CustomPacket\event;

use ifteam\CustomPacket\Packet\CustomRawPacket;

class ReceiveJSONPacketEvent extends ReceivePacketEvent {
	public function __construct(CustomRawPacket $packet, $ip, $port) {
		parent::__construct ($packet, $ip, $port );
	}
}

?>
