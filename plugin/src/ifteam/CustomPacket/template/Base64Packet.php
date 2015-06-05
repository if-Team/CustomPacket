<?php

namespace ifteam\CustomPacket\template;

use ifteam\CustomPacket\DataPacket;
use ifteam\CustomPacket\Info;

class Base64Packet extends DataPacket{ 
    
    private $decoded = NULL;

    public function pid(){
        return Info::PKHEAD_BASE64;
    }

    public function getDecoded(){
        return ($this->decoded = NULL ? ($this->decoded = base64_decode(substr($this->data, 1))) : $this->decoded);
    }

    public static function fromString($address, $port, $str){
        return new Base64Packet($address, $port, base64_encode($str));
    }
}