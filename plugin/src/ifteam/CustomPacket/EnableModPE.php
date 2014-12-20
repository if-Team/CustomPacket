<?php

namespace ifteam\CustomPacket;

use pocketmine\event\Listener;
use pocketmine\plugin\Plugin;
use ifteam\CustomPacket\event\ReceiveJSONPacketEvent;

class EnableModPE implements Listener {
    public function __construct(Plugin $plugin) {
        $plugin->getServer ()->getPluginManager ()->registerEvents ( $this, $plugin );
    }
    public function handleDataPacket(ReceiveJSONPacketEvent $event) {
        $p = explode ( '-', $event->getPacket () );
        if ($p [0] != 'PE')
            return;
        /**
         * @TODO WELCOME TO HELLGATE
         */
        switch ($p [1]) {
            // NORMAL PACKET
            case ModPEProtocol::SPAWN_MOB :
                break;
            case ModPEProtocol::EXPLODE :
                break;
            case ModPEProtocol::GET_GAMEMODE :
                break;
            case ModPEProtocol::SET_GAMEMODE :
                break;
            case ModPEProtocol::SET_NIGHT_MODE :
                break;
            case ModPEProtocol::SET_POSITION :
                break;
            case ModPEProtocol::SET_POSITION_RELATIVE :
                break;
            case ModPEProtocol::SET_ROTATION :
                break;
            case ModPEProtocol::SET_TILE :
                break;
            case ModPEProtocol::SET_VEL_POSITION :
                break;
            // LEVEL PACKET
            case ModPEProtocol::ADD_PARTICLE :
                break;
            case ModPEProtocol::GET_BLOCK_DATA :
                break;
            case ModPEProtocol::DESTROY_BLOCK :
                break;
            case ModPEProtocol::DROPITEM :
                break;
            case ModPEProtocol::GET_CHEST_SLOT :
                break;
            case ModPEProtocol::GET_CHEST_SLOT_COUNT :
                break;
            case ModPEProtocol::GET_CHEST_SLOT_DATA :
                break;
            case ModPEProtocol::SET_CHEST_SLOT :
                break;
            case ModPEProtocol::GET_FURNACE_SLOT :
                break;
            case ModPEProtocol::GET_FURNACE_SLOT_COUNT :
                break;
            case ModPEProtocol::GET_FURNACE_SLOT_DATA :
                break;
            case ModPEProtocol::SET_FURNACE_SLOT :
                break;
            case ModPEProtocol::GET_SIGN_TEXT :
                break;
            case ModPEProtocol::SET_SIGN_TEXT :
                break;
            case ModPEProtocol::GET_TIME :
                break;
            case ModPEProtocol::SET_TIME :
                break;
            case ModPEProtocol::GET_WORLD_NAME :
                break;
            case ModPEProtocol::PLAY_SOUND :
                break;
            case ModPEProtocol::PLAY_SOUND_ENT :
                break;
            // PLAYER PACKET
            case ModPEProtocol::ADD_ITEM_INVENTORY :
                break;
            case ModPEProtocol::ADD_ITEM_CREATIVE_INV :
                break;
            case ModPEProtocol::CAN_FLY :
                break;
            case ModPEProtocol::CLEAR_INVENTORY_SLOT :
                break;
            case ModPEProtocol::GET_ARMOR_SLOT :
                break;
            case ModPEProtocol::GET_CARRIED_ITEM :
                break;
            case ModPEProtocol::GET_CARRIED_ITEM_COUNT :
                break;
            case ModPEProtocol::GET_CARRIED_ITEM_DATA :
                break;
            case ModPEProtocol::SET_CARRIED_ITEM :
                break;
            case ModPEProtocol::GET_INVENTORY_SLOT :
                break;
            case ModPEProtocol::GET_INVENTORY_SLOT_COUNT :
                break;
            case ModPEProtocol::GET_INVENTORY_SLOT_DATA :
                break;
            case ModPEProtocol::GET_POINTED_BLOCK_DATA :
                break;
            case ModPEProtocol::GET_POINTED_ENTITY :
                break;
            case ModPEProtocol::GET_SELECTED_SLOT_ID :
                break;
            case ModPEProtocol::GET_ENTITY_POSITION_DATA :
                break;
            case ModPEProtocol::SET_ARMOR_SLOT :
                break;
            case ModPEProtocol::GET_HEALTH :
                break;
            case ModPEProtocol::SET_HEALTH :
                break;
            // ENTITY PACKET
            case ModPEProtocol::ENTITY_GET_ALL :
                break;
            case ModPEProtocol::GET_ENTITY_TYPE_ID :
                break;
            case ModPEProtocol::SET_MOB_SKIN :
                break;
            case ModPEProtocol::GET_MOB_SKIN :
                break;
            case ModPEProtocol::GET_NAME_TAG :
                break;
            case ModPEProtocol::GET_UNIQUE_ID :
                break;
            case ModPEProtocol::ENTITY_REMOVE :
                break;
            case ModPEProtocol::SET_FIRE_TICKS :
                break;
            // ITEM PACKET
            case ModPEProtocol::GET_ITEM_NAME :
                break;
            case ModPEProtocol::SET_ITEM_MAX_DAMAGE :
                break;
            // SERVER PACKET
            case ModPEProtocol::GET_ALL_PLAYER_NAMES :
                break;
            case ModPEProtocol::GET_ALL_PLAYERS :
                break;
            case ModPEProtocol::SEND_CHAT :
                break;
            // SPECIAL_PROCESS
            case ModPEProtocol::GET_PLAYER_ENTITY :
                break;
            case ModPEProtocol::GET_PLAYER_NAME :
                break;
            case ModPEProtocol::GET_PLAYER_FLYING :
                break;
            case ModPEProtocol::GET_IS_PLAYER :
                break;
            case ModPEProtocol::GET_ENTITY :
                break;
            case ModPEProtocol::GET_LEVEL :
                break;
            case ModPEProtocol::GET_TILE :
                break;
            case ModPEProtocol::PREVENT_DEFAULT :
                break;
        }
    }
}
