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

        $guild = Main::getInstance()->getGuildManager()->getGuildAtPosition($block->getPosition());

        if($guild !== null) {
            $user = Main::getInstance()->getUserManager()->getUser($player->getName());

            if ($user->getGuild() === null) {
                $event->cancel();
                return;
            }

            if ($user->getGuild()->getTag() !== $guild->getTag())
                $event->cancel();
        }
    }
}