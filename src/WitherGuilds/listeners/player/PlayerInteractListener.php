<?php

namespace WitherGuilds\listeners\player;

use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use WitherGuilds\Main;
use WitherGuilds\utils\ChatUtil;

class PlayerInteractListener implements Listener {

    public function onBreakHeart(PlayerInteractEvent $event) {
        $block = $event->getBlock();
        $player = $event->getPlayer();

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