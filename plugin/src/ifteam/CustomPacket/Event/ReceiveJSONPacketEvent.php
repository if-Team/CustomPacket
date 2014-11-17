<?php

namespace ifteam\CustomPacket\event;

class ReceiveJSONPacketEvent extends ReceivePacketEvent {
	public function __construct(PluginBase $plugin, $packet, $ip, $port) {
		parent::__construct ( $plugin, $packet, $ip, $port );
	}
}

?>
