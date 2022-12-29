<?php

namespace WitherGuilds\utils;

use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;

class ConfigUtil {

    public const HEART_Y = 30;
    public const DEFAULT_SIZE = 30;
    public const MAX_SIZE = 81;
    public const SHAPE_GUILD_XZ = 6;
    public const SHAPE_GUILD_Y = 4;
    
    public const HEART_ID = 19;
    public const HEART_META = 0;

    public const START_HP = 3;
    public const MAX_HP = 5;

    public const TITLE = "&l&6GILDIE";
    public const ENTERED_OWN_GUILD = "&aWszedles na teren swojej gildii!";
    public const LEAVE_OWN_GUILD = "&aOpusciles teren swojej gildii!";
    public const ENTERED_ENEMY_GUILD = "&cWszedles na teren wrogiej gildii!";
    public const LEAVE_ENEMY_GUILD = "&cOpusciles teren wrogiej gildii!";

    public const BOSSBAR_OWN_GUILD = "&aTeren twojej gildii [{TAG}] - [{GUILD}]";
    public const BOSSBAR_ENEMY_GUILD = "&cTeren wrogiej gildii [{TAG}] - [{GUILD}]";

    public static function getGuildItems() : array {
        return [
            ItemFactory::getInstance()->get(ItemIds::DIAMOND, 0, 64),
            ItemFactory::getInstance()->get(ItemIds::EMERALD, 0, 64),
            ItemFactory::getInstance()->get(ItemIds::GOLD_INGOT, 0, 64)
        ];
    }
}