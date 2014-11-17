<?php

namespace ifteam\CustomPacket;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\event\Listener;

class CustomPacket extends PluginBase implements Listener {

    /** @var CustomSocket */
    private $socket;
    private $option;
    
    private static $instance = null;
    
    public function onEnable() {
        $option = [ "port" => 19131 ];
        $this->option = (new Config($this->getDataFolder() . "SocketOption.yml", Config::YAML, $option))->getAll();
        $this->socket = new CustomSocket($this->option["port"]);
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
    public function getDefaultPort() {
        return $this->option["port"];
    }
    /**
     * ReceivePacket and callEvent ReceivePacketEvent
     * or ReceiveJSONPacketEvent (is_numeric check)
     *
     * @param int|string $data
     * @param int $ip
     * @param int $port
     *
     */
    public function receivePacket($data, $ip, $port) {
    	$this->socket->receivePacket($data, $ip, $port);
    }
    /**
     * SendPacket and callEvent SendPacketEvent
     * or SendJSONPacketEvent (is_numeric check)
     *
     * @param int|string $packet
     * @param string $ip
     * @param int $port
     *
     */
    public static function sendPacket($packet, $ip, $port) {
    	$this->socket->sendPacket($packet, $ip, $port);
    }
}
?>
