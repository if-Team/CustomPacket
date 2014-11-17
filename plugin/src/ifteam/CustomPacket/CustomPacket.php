<?php

namespace ifteam\CustomPacket;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use ifteam\CustomPacket\event\ReceiveJSONPacketEvent;
use ifteam\CustomPacket\event\SendJSONPacketEvent;
use ifteam\CustomPacket\event\ReceivePacketEvent;
use ifteam\CustomPacket\event\SendPacketEvent;

class CustomPacket extends PluginBase implements Listener {

    /** @var CustomSocket */
    private $socket;
    private $option;
    
    private static $instance = null;
    
    public function onEnable() {
        $option = [
            "ip" => "0.0.0.0",
            "port" => 19131
        ];
        $this->option = (new Config($this->getDataFolder() . "SocketOption.yml", Config::YAML, $option))->getAll();
        $this->socket = new CustomSocket($this, $this->option["ip"], $this->option["port"]);
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
    
    public function onDisable(){
        $this->socket->close();
        usleep(50000);
        $this->socket->kill();
    }
    
    /** @return CustomPacket */
    public static function getInstance(){
    	if(self::$instance == null)
    		self::$instance = new self;
    	return self::$instance;
    }
    
    /** @return int */
    public function getDefaultIp() {
        return $this->option["ip"];
    }
    
    
    /** @return int */
    public function getDefaultPort() {
        return $this->option["port"];
    }
    /**
     * ReceivePacket and callEvent ReceivePacketEvent
     * or ReceiveJSONPacketEvent (is_numeric check)
     *
     * @param PluginBase $plugin
     * @param int|string $data            
     * @param int $ip            
     * @param int $port            
     *
     */
    public function receivePacket(PluginBase $plugin, $data, $ip, $port) {
        if(is_numeric($data)){
            $event = new ReceivePacketEvent($plugin, $data, $ip, $port);
            $this->getServer()->getPluginManager()->callEvent($event);
        }else{
            $event = new ReceiveJSONPacketEvent($plugin, JSON_Decode($data), $ip, $port);
            $this->getServer()->getPluginManager()->callEvent($event);
        }
    }
    /**
     * SendPacket and callEvent SendPacketEvent
     * or SendJSONPacketEvent (is_numeric check)
     *
     * @param PluginBase $plugin
     * @param int|string $packet
     * @param string $ip            
     * @param int $port            
     *
     */
    public static function sendPacket(PluginBase $plugin, $packet, $ip, $port) {
        if(is_numeric($packet)){
            $event = new SendPacketEvent($plugin, $packet, $ip, $port);
            $this->getServer ()->getPluginManager ()->callEvent ($event);
            if(!$event->isCancelled()){
                $packet = $event->getPacket();
                @socket_sendto($this->socket->socket, $packet, \strlen($packet), 0, $ip, $port);
            }
        }else{
            $event = new SendJSONPacketEvent($plugin, $packet, $ip, $port);
            $this->getServer()->getPluginManager()->callEvent($event);
            if(!$event->isCancelled()){
                $packet = JSON_Encode($event->getPacket());
                @socket_sendto ($this->socket->socket, $packet, \strlen($packet), 0, $ip, $port);
            }
        }
    }
}
?>
