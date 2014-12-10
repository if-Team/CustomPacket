<?php

namespace ifteam\CustomPacket;

interface ModPEProtocol {

    //PACKET VERSION
    const PROTOCOL_VERSION = 0.1

    // NORMAL PACKET
    const SPAWN_MOB = 0x40;
    const EXPLODE = 0x41;
    const GET_GAMEMODE = 0x42;
    const SET_GAMEMODE = 0x43;
    const SET_NIGHT_MODE = 0x44;
    const SET_POSITION = 0x45;
    const SET_POSITION_RELATIVE = 0x46;
    const SET_ROTATION = 0x47;
    const SET_TILE = 0x48;
    const SET_VEL_POSITION = 0x49;
    
    // LEVEL PACKET
    const ADD_PARTICLE = 0x50;
    const GET_BLOCK_DATA = 0x51;
    const DESTROY_BLOCK = 0x52;
    const DROPITEM = 0x53;
    const GET_CHEST_SLOT = 0x54;
    const GET_CHEST_SLOT_COUNT = 0x55;
    const GET_CHEST_SLOT_DATA = 0x56;
    const SET_CHEST_SLOT = 0x57;
    const GET_FURNACE_SLOT = 0x58;
    const GET_FURNACE_SLOT_COUNT = 0x59;
    const GET_FURNACE_SLOT_DATA = 0x60;
    const SET_FURNACE_SLOT = 0x61;
    const GET_SIGN_TEXT = 0x62;
    const SET_SIGN_TEXT = 0x63;
    const GET_TIME = 0x64;
    const SET_TIME = 0x65;
    const GET_WORLD_NAME = 0x66;
    const PLAY_SOUND = 0x67;
    const PLAY_SOUND_ENT = 0x68;
    
    // PLAYER PACKET
    const ADD_ITEM_INVENTORY = 0x69;
    const ADD_ITEM_CREATIVE_INV = 0x70;
    const CAN_FLY = 0x71;
    const CLEAR_INVENTORY_SLOT = 0x72;
    const GET_ARMOR_SLOT = 0x73;
    const GET_CARRIED_ITEM = 0x74;
    const GET_CARRIED_ITEM_COUNT = 0x75;
    const GET_CARRIED_ITEM_DATA = 0x76;
    const SET_CARRIED_ITEM = 0x77;
    const GET_INVENTORY_SLOT = 0x78;
    const GET_INVENTORY_SLOT_COUNT = 0x79;
    const GET_INVENTORY_SLOT_DATA = 0x80;
    const GET_POINTED_BLOCK_DATA = 0x81;
    const GET_POINTED_ENTITY = 0x82;
    const GET_SELECTED_SLOT_ID = 0x83;
    const GET_ENTITY_POSITION_DATA = 0x84;
    const SET_ARMOR_SLOT = 0x85;
    const GET_HEALTH = 0x86;
    const SET_HEALTH = 0x87;
    
    // ENTITY PACKET
    const ENTITY_GET_ALL = 0x88;
    const GET_ENTITY_TYPE_ID = 0x89;
    const SET_MOB_SKIN = 0x90;
    const GET_MOB_SKIN = 0x91;
    const GET_NAME_TAG = 0x92;
    const GET_UNIQUE_ID = 0x93;
    const ENTITY_REMOVE = 0x94;
    const SET_FIRE_TICKS = 0x95;
    
    // ITEM PACKET
    const GET_ITEM_NAME = 0x96;
    const SET_ITEM_MAX_DAMAGE = 0x97;
    
    // SERVER PACKET
    const GET_ALL_PLAYER_NAMES = 0x98;
    const GET_ALL_PLAYERS = 0x99;
    const SEND_CHAT = 0xa0;
    
    // SPECIAL_PROCESS
    const GET_PLAYER_ENTITY = 0xa1;
    const GET_PLAYER_NAME = 0xa2;
    const GET_PLAYER_FLYING = 0xa3;
    const GET_IS_PLAYER = 0xa4;
    const GET_ENTITY = 0xa5;
    const GET_LEVEL = 0xa6;
    const GET_TILE = 0xa7;
    const PREVENT_DEFAULT = 0xa8;
    
    // SYSTEM SIGNALS
    const SIGNAL_HANDSHAKE = 0xb0;
    const SIGNAL_LOGIN = 0xb2;
    const SIGNAL_LOGOUT = 0xb3;
    const SIGNAL_PING = 0xb4;
    const SIGNAL_PONG = 0xb5;
    const SIGNAL_NOTHING = 0xb6;
    
    //0xc0 ~ 0xef: Reserved
    
    // Raw Packet
    const PACKET_UNKNOWN = 0xf0;
    const PACKET_RAW_STRING = 0xf1;
    const PACKET_RAW_BASE64 = 0xf2;
    
    // Super secret code. Let's keep this secret.
    const SIGNAL_WOW = 0xff;
}

?>