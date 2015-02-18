<?php

namespace ifteam\CustomPacket;

use pocketmine\Server;

class CPAPI{
    
    private static $interface;
    
    public static function sendPacket(DataPacket $packet){
        return MainLoader::getInterface()->sendPacket($packet);
    }
    
    public static function matchPlayer($ip){
        foreach(Server::getInstance()->getOnlinePlayers() as $p){
            if($p->getAddress() === $ip) return $p;
        }
        return null;
    }
    
}