<?php

namespace ifteam\CustomPacket\event;

use pocketmine\plugin\PluginBase;
use pocketmine\event\plugin\PluginEvent;

class CustomPacketEvent extends PluginEvent{
    protected $plugin, $packet, $ip, $port;
    public static $handlerList = null;
    
    public function __construct(CustomSocket $plugin, $packet, $ip, $port) {
        $this->plugin = $plugin;
        $this->packet = $packet;
        $this->ip = $ip;
        $this->port = $port;
    }
    
    public function getPlugin() {
        return $this->plugin;
    }
}
?>
