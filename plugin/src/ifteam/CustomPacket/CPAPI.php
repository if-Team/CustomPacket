<?php

namespace ifteam\CustomPacket;

use pocketmine\Server;

class CPAPI{

    private static $interface, $task;

    public static function sendPacket(DataPacket $packet){
        return MainLoader::getInterface()->sendPacket($packet);
    }

    public static function matchPlayer($ip){
        foreach(Server::getInstance()->getOnlinePlayers() as $p){
            if($p->getAddress() === $ip) return $p;
        }
        return null;
    }

    public static function blockAddress($address, $seconds){
        MainLoader::getInterface()->blockAddress($address, $seconds);
    }

    public static function unblockAddress($address){
        MainLoader::getInterface()->unblockAddress($address);
    }

    public static function registerHaltTask($callback, $args = []){
        self::$task[] = [$callback, $args];
    }

    public static function getHaltTasks(){
        return self::$task;
    }

}
