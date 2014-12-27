<?

namespace ifteam\CustomPacket;

use ifteam\CustomPacket\Info;

require("Info.php");
require("DataPacket.php");

class CustomSocket extends \Thread{
    
    protected $internalQueue, $externalQueue, $logger, $port, $interface, $shutdown, $socket, $lastmeasure;
    
    public function __construct(\Threaded $internalThread, \Threaded $externalThread, \ThreadedLogger $logger, $port, $interface = "0.0.0.0"){
        $this->internalQueue = $internalThread; //Used to contain internal signals
        $this->externalQueue = $externalThread; //Used to contain received packets
        $this->logger = $logger;
        $this->port = $port;
        $this->interface = $interface;
        $this->shutdown = false;
        $this->logInfo("Initialization compelete. Starting thread...");
        $this->start();
    }
    
    public function pushMainQueue(DataPacket $packet){
        $this->externalQueue[] = $packet;
    }
    
    public function readMainQueue(){
        return $this->externalQueue->shift();
    }
    
    public function pushInternalQueue(array $buffer){
        $this->internalQueue[] = $buffer;
    }
    
    public function readInternalQueue(){
        return $this->internalQueue->shift();
    }
    
    public function logInfo($str){
        $this->logger->info("[CustomSocket Thread #".\Thread::getCurrentThreadId()."] $str");
    }
    
    public function logEmergency($str){
        $this->logger->emergency("[CustomSocket Thread #".\Thread::getCurrentThreadId()."] $str");
    }
    
    public function doTick(){
        $this->lastmeasure = microtime(true);
        $timeout = 50000;
        while(!$this->shutdown and $timeout >= 0){
            $address = $port = $buffer = null;
            if(@socket_recvfrom($this->socket, $buffer, 65535, 0, $address, $port) > 0){
                $this->pushMainQueue(new DataPacket($address, $port, $buffer));
            }
            $timeout = $this->lastmeasure - microtime(true);
        }
    }
    
    public function run(){
        $this->logInfo("Thread started.");
        $this->logInfo("Starting thread...");
        $this->socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        if(@socket_bind($this->socket, $this->interface, $this->port) === true){
            socket_set_option($this->socket, SOL_SOCKET, SO_REUSEADDR, 0);
            @socket_set_option($this->socket, SOL_SOCKET, SO_SNDBUF, 1024*1024*8);
            @socket_set_option($this->socket, SOL_SOCKET, SO_RCVBUF, 1024*1024);
            socket_set_nonblock($this->socket);
            $this->logInfo("Succeeded creating socket.");
        } else {
            $this->shutdown = true;
            $this->logEmergency("*** Failed to bind to ".$this->interface.":".$this->port."!");
            $this->logEmergency("*** Perhaps another program is already using this port?");
        }
        while($this->shutdown === false){
            if(is_array($buffer = $this->readInternalQueue())){
                switch(ord($buffer[0]{0})){
                    case Info::SIGNAL_UPDATE:
                        break;
                        
                    case Info::SIGNAL_SHUTDOWN:
                        $this->shutdown = true;
                        break;
                        
                    case Info::SIGNAL_TICK:
                        $this->doTick();
                        break;
                }
            }
        }
        $this->logEmergency("CustomSocket stopped!");
        @socket_close($this->socket);
    }
    
    public function shutdown(){
        $this->pushInternalQueue([chr(Info::SIGNAL_SHUTDOWN)]);
    }
    
}