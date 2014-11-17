<?php

namespace ifteam\CustomPacket\event;

use pocketmine\event\Cancellable;

class SendPacketEvent extends CustomPacketEvent implements Cancellable{
	public function __construct(PluginBase $plugin, $packet, $ip, $port) {
		parent::__construct ( $plugin, $packet, $ip, $port );
	}
	
    public function getPacket() {
        return $this->packet;
    }
    
    public function setPacket($packet){
        if(!is_numeric($packet)) return;
        $this->packet = $packet;
    }
    
    public function getIp() {
        return $this->ip;
    }
    
    public function getPort() {
        return $this->port;
    }
}

?>
