<?php

namespace ifteam\CustomPacket\Event;

use ifteam\CustomPacket\Packet\CustomRawPacket;
use pocketmine\event\Cancellable;

class SendRawPacketEvent extends SendPacketEvent implements Cancellable{
	public function __construct(CustomRawPacket $packet, $ip, $port) {
		parent::__construct ($packet, $ip, $port );
	}
    public function setPacket($packet){
        if(is_numeric($packet)) return;
        $this->packet = $packet;
    }
    
}

?>
