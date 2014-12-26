<?

class SocketInterface extends \Thread{
    
    protected $internalQueue, $externalQueue, $logger, $port, $interface, $shutdown;
    
    public function __construct(\Threaded $inputThread, \Threaded $outputThread, \ThreadedLogger $logger, $port, $interface = "0.0.0.0"){
        $this->internalQueue = $internalThread;
        $this->externalQueue = $externalThread;
        $this->logger = $logger;
        $this->port = $port;
        $this->interface = $interface;
        $this->shutdown = false;
        $this->logInfo("Initialization compelete. Starting thread...");
        $this->start();
    }
    
    public function pushMainQueue($buffer){
        $this->externalQueue[] = $buffer;
    }
    
    public function readMainQueue(){
        return $this->externalQueue->shift();
    }
    
    public function pushInternalQueue($buffer){
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
            if(strlen($buffer = $this->readInternalQueue()) > 0){
                switch(ord($buffer{0})){
                    case Info::SIGNAL_SHUTDOWN:
                        $this->shutdown = true;
                        break;
                    //TODO
                }
            }
        }
        $this->logEmergency("CustomSocket crashed!");
    }
    
    public function shutdown(){
        $this->pushInternalQueue(chr(Info::SIGNAL_SHUTDOWN));
    }
    
}