<?php

namespace WitherGuilds\utils;

use pocketmine\block\BlockFactory;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\world\Position;

class ShapesUtil {

    public static function create(Position $heartPosition): void {
        $rXZ = floor(ConfigUtil::SHAPE_GUILD_XZ/2);
        $rY = floor(ConfigUtil::SHAPE_GUILD_Y/2);
        $yArray = [];

        //AIR
        for($x = $heartPosition->getFloorX()-$rXZ; $x <= $heartPosition->getFloorX()+$rXZ; $x++) {
            for ($z = $heartPosition->getFloorZ()-$rXZ; $z <= $heartPosition->getFloorZ()+$rXZ; $z++) {
                for ($y = $heartPosition->getFloorY()-($rY-1); $y <= $heartPosition->getFloorY()+$rY; $y++) {
                    $yArray[] = $y;
                    $heartPosition->getWorld()->setBlock(new Position($x, $y, $z, $heartPosition->getWorld()), VanillaBlocks::AIR());
                }
            }
        }

        //FLOOR

        for ($x = ($heartPosition->getFloorX()-$rXZ); $x <= ($heartPosition->getFloorX()+$rXZ); $x++) {
            for ($z = ($heartPosition->getFloorZ()-$rXZ); $z <= ($heartPosition->getFloorZ()+$rXZ); $z++) {
                $heartPosition->getWorld()->setBlock(new Position($x, min($yArray), $z, $heartPosition->getWorld()), VanillaBlocks::OBSIDIAN());
            }
        }

        $heartPosition->getWorld()->setBlock($heartPosition, BlockFactory::getInstance()->get(ConfigUtil::HEART_ID, ConfigUtil::HEART_META));
    }
}