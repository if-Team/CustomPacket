<?php

namespace ifteam\CustomPacket\event;

use pocketmine\plugin\PluginBase;

class ReceivePacketEvent extends CustomPacketEvent{
	public function __construct(PluginBase $plugin, $packet, $ip, $port) {
		parent::__construct ( $plugin, $packet, $ip, $port );
	}
    public function getPacket() {
        return $this->packet;
    }
    
    public function getIp() {
        return $this->ip;
    }
    
    public function getPort() {
        return $this->port;
    }
}

?>