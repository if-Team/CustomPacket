<?php

namespace ifteam\CustomPacket;

//List of CustomPacket packet headers

class Info{
    
    //Internal signals. DO NOT USE THESE FOR EXTERNAL PACKET.
    const PACKET_SEND = 0x01;
    //const PACKET_RECV = 0x02;
    //const PACKET_SEND_ENCRYPTED = 0x03; //Reserved for future use
    //const PACKET_RECV_ENCRYPTED = 0x04; //Reserved for future use
    //0x04~0x0x0f: Reserved
    //External packet headers.
    const PKHEAD_INVALID = 0xa0;
    const PKHEAD_DATA = 0xa1;
    const PKHEAD_BASE64 = 0xa2;
    const PKHEAD_ENUM = 0xa3;
    //~0xdf: reserved
    const SIGNAL_UPDATE = 0xf0;
    const SIGNAL_TICK = 0xf1;
    const SIGNAL_BLOCK = 0xf2;
    const SIGNAL_UNBLOCK = 0xf3;
    const SIGNAL_SHUTDOWN = 0xff;
    
}