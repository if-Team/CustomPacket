<?php

namespace ifteam\CustomPacket\Packet;

use ifteam\CustomPacket\ModPEProtocol as Protocol;
use pocketmine\Server;

class CustomPacket{
    
    private $type = Protocol::PACKET_UNKNOWN;
    protected $data;
    protected $rawdata;
    
    public function __construct($rawstring){
        $this->rawdata =     $rawstring;
        $decoded = $this->splitHeader($rawstring);
        $json = json_decode($decoded[1], true);
        if($json === false){
            $pk = new CustomJSONPacket($json, $decoded[0]);
        } else if($decoded[0] === Protocol::PACKET_RAW_BASE64){
            $pk = new CustomEncodedPacket($decoded[1], $decoded[0]);
        } else {
            $pk = new CustomRawPacket($decoded[1], $decoded[0]);
        }
        return $pk;
    }
    
    protected function splitHeader($string){
        return array(mb_substr($string, 0, 1), mb_substr($string, 1));
    }
    
    public function getPacketID(){
        return $this->type;
    }
    
    public function printDump(){
        $logger = Server::getInstance()->getLogger();
        $cnt = 0;
        $lines = array();
        $line = '';
        $offset = 0x00;
        $printValue = str_split($this->rawdata, 10);
        foreach(str_split($this->rawdata) as $letter){
            if($cnt === 0){
                $line .= '| 0x'. sprintf("%1$08x", $offset) . ' : ';
            }
            $cnt++;
            $line .= ord($letter) . ' ';
            if($cnt === 10){
                $line .= '|| '. current($printValue) . ' |';
                next($printValue);
                $lines[] = $line;
                $line = '';
                $cnt = 0;
            }
            $offset++;
        }
        
        $logger->info("[CustomPacket] Start packet hexdump...");
        $logger->info("");
        $logger->info("Packet type: ". $this->getPacketID());
        $logger->info("Packet length: ". strlen($this->rawdata));
        $logger->info("");
        $logger->info(str_repeat('=', 60));
        $logger->info('|' .str_repeat(' ', 58) . '|');
        foreach($lines as $l){
            $logger->info($l);
        }
        
        $logger->info('|' .str_repeat(' ', 58) . '|');
        $logger->info(str_repeat('=', 60));
        $logger->info("");
        $logger->info("[CustomPacket] End packet hexdump...");
        
    }
    
}
?>