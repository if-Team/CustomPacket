<?php

namespace ifteam\CustomPacket\Packet;

class CustomEncodedPacket extends CustomPacket{
    
    public function __construct($decodedString, $pid){
        $this->data = $decodedString;
        $this->type = $pid;
        $this->decodeSelf();
    }
    
    public function decodeSelf(){
        $this->data = base64_decode($this->data);
    }
    
    public function encodeSelf(){
        $this->data = base64_encode($this->data);
    }
}
?>