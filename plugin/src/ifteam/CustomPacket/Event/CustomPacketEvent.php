<?php

namespace ifteam\CustomPacket\event;

use pocketmine\plugin\PluginBase;
use pocketmine\event\plugin\PluginEvent;
use ifteam\CustomPacket\Packet\CustomPacket;

class CustomPacketEvent extends PluginEvent{
    protected $plugin, $packet, $ip, $port;
    public static $handlerList = null;
    
    public function __construct(CustomPacket $packet, $ip, $port) {
        $this->plugin = $plugin;
        $this->packet = $packet;
        $this->ip = $ip;
        $this->port = $port;
    }
    
    public function getPlugin() {
        return $this->plugin;
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
