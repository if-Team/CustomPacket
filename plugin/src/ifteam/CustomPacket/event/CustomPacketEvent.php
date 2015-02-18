<?php

namespace ifteam\CustomPacket\event;

use pocketmine\event\Event;
use pocketmine\Player;
use ifteam\CustomPacket\DataPacket;

class CustomPacketEvent extends Event{
    
    private $packet;
    private $player;
    
    public function __construct(DataPacket $packet, Player $player){
        $this->packet = $packet;
        $this->player = $player;
    }
    
    public function getPacket(){
        return $this->packet;
    }
    
    public function getPlayer(){
        return $this->player;
    }
    
}