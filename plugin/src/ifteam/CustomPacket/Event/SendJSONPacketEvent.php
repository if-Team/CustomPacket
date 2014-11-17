<?php

namespace ifteam\CustomPacket\Event;

use pocketmine\event\Cancellable;

class SendJSONPacketEvent extends SendPacketEvent implements Cancellable{
	public function __construct(PluginBase $plugin, $packet, $ip, $port) {
		parent::__construct ( $plugin, $packet, $ip, $port );
	}
    public function setPacket($packet){
        if(is_numeric($packet)) return;
        $this->packet = $packet;
    }
    
}

?>
