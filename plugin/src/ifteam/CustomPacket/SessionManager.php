<?php

namespace ifteam\CustomPacket;

use pocketmine\Player;
use pocketmine\Server;

class SessionManager {
    
    private static $instance;
    private $session = array();
    private $log = 'Server::getInstance()->getLogger()->debug';
    
    public function __construct(){
        self::$instance = $this;
    }
    
    public static function getInstance(){
        return self::$instance;
    }
    
    public function add(Player $player, $ip, $port){
        if($player->getAddress() !== $ip): Server::getInstance()->getLogger()->warning('Player '.$player->getName().' attempted to connect to CustomPacket server with invalid IP!'); return; endif;
        $this->session[$player->getName()]['ip'] = $ip;
        $this->session[$player->getName()]['port'] = $port;
        $this->log($player->getName().' ('.$ip.')connected to CustomPacket server on port '. $port);
    }
    
    public function close($key){
        if(isset($this->session[$key])): $this->session[$key] = NULL; $this->log('Player '. $key. 'disconnected from CustomPacket Server'); endif; //faster freeing memory without garbage collector
    }
    
    public function getSessionData($key){
        return isset($this->session[$key]) ? $this->session[$key] : false;
    }
    
}
?>
