<?php

namespace ifteam\CustomPacket\Packet;

class CustomJSONPacket extends CustomPacket{
    
    public function __construct($decodedString, $pid){
        $this->data = $decodedString;
        $this->type = $pid;
    }
}

