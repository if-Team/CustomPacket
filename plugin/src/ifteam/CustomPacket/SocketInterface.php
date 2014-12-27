<?

namespace ifteam\CustomPacket;

use pocketmine\Server;

class SourceInterface{
    
    private $internalThreaded;
    private $externalThreaded;
    private $server;
    private $socket;
    
    public function __construct(Server $server){
        $this->internalThreaded = new \Threaded();
        $this->externalThreaded = new \Threaded();
        $this->server = $server;
        $this->socket = new CustomSocket($this->internalThreaded, $this->externalThreaded, $this->server->getLogger(), 19131, $this->server->getIp() === "" ? "0.0.0.0" : $this->server->getIp());
    }
    
    public function process(){
        $work = false;
        if($this->handlePacket()){
            $work = true;
            while($this->handlePacket());
        }
        $this->pushInternalQueue([chr(Info::SIGNAL_TICK)]);
        return $work;
    }
    
    public function handlePacket(){
        if(strlen($packet = $this->readMainQueue()) > 0){
            switch(ord($packet[0]{0})){
                
            }
            return true;
        }
        
        return false;
    }
    
    public function pushMainQueue(array $buffer){
        $this->exteranlThreaded[] = $buffer;
    }
    
    public function readMainQueue(){
        return $this->externalThreaded->shift();
    }
    
    public function pushInternalQueue(array $buffer){
        $this->internalThreaded = $buffer;
    }
    
    public function readInternalQueue(){
        return $this->internalThreaded->shift();
    }
    
}