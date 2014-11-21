<?php

namespace ifteam\CustomPacket\event;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Event;
use ifteam\CustomPacket\Packet\CustomPacket;

class CustomPacketEvent extends Event{
    protected $packet, $ip, $port;
    public static $handlerList = null;
    
    public function __construct(CustomPacket $packet, $ip, $port) {
        $this->packet = $packet;
        $this->ip = $ip;
        $this->port = $port;
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
