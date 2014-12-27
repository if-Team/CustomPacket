<?

namespace ifteam\CustomPacket;

//List of CustomPacket packet headers

class Info{
    
    //Internal signals. DO NOT USE THESE FOR EXTERNAL PACKET.
    const PACKET_SEND = 0x01;
    const PACKET_RECV = 0x02;
    const PACKET_SEND_ENCRYPTED = 0x03; //Reserved for future use
    const PACKET_RECV_ENCRYPTED = 0x04; //Reserved for future use
    //0x05~0xdf: Reserved
    const SIGNAL_UPDATE = 0xf0;
    const SIGNAL_TICK = 0xf1;
    const SIGNAL_SHUTDOWN = 0xff;
    
    //External packet headers.(TODO)
    
}