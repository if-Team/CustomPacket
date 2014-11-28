<?php

namespace ifteam\CustomPacket\event;

use pocketmine\event\Cancellable;
use ifteam\CustomPacket\Packet\CustomPacket;

class SendPacketEvent extends CustomPacketEvent implements Cancellable{
	public function __construct(CustomPacket $packet, $ip, $port) {
		parent::__construct ($packet, $ip, $port );
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
