<?

namespace ifteam\CustomPacket;

use pocketmine\Server;

class DataPacket{ //Note: need to be abstract in future
    
    private $address;
    private $port;
    private $data;
    
    public function __construct($address, $port, $data){
        $this->address = $address;
        $this->port = $port;
        $this->data = $data;
    }
    
    public function printDump(){
        $logger = Server::getInstance()->getLogger();
        $cnt = 0;
        $lines = array();
        $line = '';
        $offset = 0x00;
        $printValue = str_split($this->data, 10);
        /*
        foreach(str_split($this->data) as $letter){
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
        */
        $logger->info("[CustomPacket] Start packet hexdump...");
        $logger->info("");
        $logger->info("Source address: ". $this->address);
        $logger->info("Source port:    ". $this->port);
        $logger->info("Packet length:  ". strlen($this->data));
        $logger->info("");
        /*$logger->info(str_repeat('=', 60));
        $logger->info('|' .str_repeat(' ', 58) . '|');
        foreach($lines as $l){
            $logger->info($l);
        }
        
        $logger->info('|' .str_repeat(' ', 58) . '|');
        $logger->info(str_repeat('=', 60));
        $logger->info("");*/
        $logger->info("[CustomPacket] End packet hexdump...");
        
    }
}