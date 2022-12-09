<?php

namespace WitherGuilds\listeners\block;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use WitherGuilds\Main;
use WitherGuilds\utils\ChatUtil;
use WitherGuilds\utils\ConfigUtil;

class BlockBreakListener implements Listener {

    public function onBreakHeart(BlockBreakEvent $event) {
        $block = $event->getBlock();
        $player = $event->getPlayer();

        if (Main::getInstance()->getGuildManager()->isHeart($block)) {
            $event->cancel();
            $player->sendTip(ChatUtil::fixColors("&cNie mozesz niszczyc serca gildii!"));
        }
    }
}