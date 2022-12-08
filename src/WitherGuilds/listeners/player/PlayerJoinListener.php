<?php

namespace WitherGuilds\listeners\player;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use WitherGuilds\Main;

class PlayerJoinListener implements Listener {

    public function onJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();

        Main::getInstance()->getUserManager()->createUser($player->getName());
    }
}