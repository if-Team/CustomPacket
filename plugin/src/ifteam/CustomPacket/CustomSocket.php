<?

namespace ifteam\CustomPacket;

use ifteam\CustomPacket\Info;

require("Info.php");

class CustomSocket extends \Thread{
    
    protected $internalQueue, $externalQueue, $logger, $port, $interface, $shutdown, $socket;
    
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
    
    public function pushMainQueue(array $buffer){
        $this->externalQueue[] = $buffer;
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
    
    public function run(){
        $this->logInfo("Thread started.");
        while($this->shutdown === false){
            if(is_array($buffer = $this->readInternalQueue())){
                switch(ord($buffer[0]{0})){
                    case Info::SIGNAL_UPDATE:
                        break;
                        
                    case Info::SIGNAL_SHUTDOWN:
                        $this->shutdown = true;
                        break;
                        
                    case Info::SIGNAL_TICK:
                        //TODO
                        break;
                }
            }
        }
        $this->logEmergency("CustomSocket stopped!");
    }
    
    public function shutdown(){
        $this->pushInternalQueue([chr(Info::SIGNAL_SHUTDOWN)]);
    }
    
}