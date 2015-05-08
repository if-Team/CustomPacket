<?php

namespace ifteam\CustomPacket\event;

use pocketmine\event\Event;
use pocketmine\Player;
use ifteam\CustomPacket\DataPacket;

class CustomPacketEvent extends Event{
    
    private $packet;
    
    public function __construct(DataPacket $packet){
        $this->packet = $packet;
    }
    
    public function getPacket(){
        return $this->packet;
    }
    
}