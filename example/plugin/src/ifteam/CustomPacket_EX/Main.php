<?php

namespace ifteam\CustomPacket_EX;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use ifteam\CustomPacket\event\CustomPacketReceiveEvent;
use ifteam\CustomPacket\DataPacket;
use ifteam\CustomPacket\CPAPI;

class Main extends PluginBase implements Listener {
    
    public function onEnable(){
        if($this->getServer()->getPluginManager()->getPlugin("CustomPacket") === null){
            $this->getServer()->getLogger()->critical("[CustomPacket Example] CustomPacket plugin was not found. This plugin will be disabled.");
            $this->getServer()->getPluginManager()->disablePlugin($this);
            return;
        }
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
    
    public function onPacketReceive(CustomPacketReceiveEvent $ev){
        $this->getServer()->getLogger()->notice("[CustomPacket Example] Received custom packet. Printing dump...");
        $ev->getPacket()->printDump();
        $this->getServer()->getLogger()->notice("[CustomPacket Example] Sending example packet...");
        $pk = new DataPacket($ev->getPacket()->address, $ev->getPacket()->port, "You sent me ".$ev->getPacket()->data." and this example plugin is returning packet");
        CPAPI::sendPacket($pk);
    }
    
}

?>