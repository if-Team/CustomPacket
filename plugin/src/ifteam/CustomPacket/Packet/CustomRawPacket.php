<?php

namespace ifteam\CustomPacket\Packet;

class CustomRawPacket extends CustomPacket{
    
    public function __construct($decodedString, $pid){
        $this->data = $decodedString;
        $this->type = $pid;
    }
}

