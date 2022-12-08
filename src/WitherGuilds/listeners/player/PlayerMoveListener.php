<?php

namespace WitherGuilds\listeners\player;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use WitherGuilds\api\bossbar\BossBar;
use WitherGuilds\Main;
use WitherGuilds\utils\ChatUtil;
use WitherGuilds\utils\ConfigUtil;

class PlayerMoveListener implements Listener {

    public function movePlayer(PlayerMoveEvent $event) {
        $player = $event->getPlayer();
        $user = Main::getInstance()->getUserManager()->getUser($player->getName());
        $guildManager = Main::getInstance()->getGuildManager();
        $bossbar = $user->getBar();

        if($event->getFrom()->floor()->equals($event->getTo()->floor()))
            return;

        $guild = $guildManager->getGuildAtPosition($player->getPosition());

        if ($guildManager->guildExistsAtPosition($player->getPosition()) && !$user->isEnter()) {

            if($guildManager->isInOwnPlot($player->getPosition(), $user)) {
                $title = ConfigUtil::ENTERED_OWN_GUILD;
                $bossbarTitle = str_replace("{TAG}", $guild->getTag(), ConfigUtil::BOSSBAR_OWN_GUILD);
                $bossbarTitle = str_replace("{GUILD}", $guild->getName(), $bossbarTitle);
            } else {
                $title = ConfigUtil::ENTERED_ENEMY_GUILD;
                $bossbarTitle = str_replace("{TAG}", $guild->getTag(), ConfigUtil::BOSSBAR_ENEMY_GUILD);
                $bossbarTitle = str_replace("{GUILD}", $guild->getName(), $bossbarTitle);
            }

            $bossbar->setTitle(ChatUtil::fixColors($bossbarTitle));
            $bossbar->addPlayer($player);
            $user->setEnter(true);
            $player->sendTitle(ChatUtil::fixColors(ConfigUtil::TITLE), ChatUtil::fixColors($title), 0, 60, 0);

        } else if(!$guildManager->guildExistsAtPosition($player->getPosition()) && $user->isEnter()) {
            if($user->getGuild() === $guild) {
                $title = ConfigUtil::LEAVE_ENEMY_GUILD;
            } else {
                $title = ConfigUtil::LEAVE_OWN_GUILD;
            }

            $user->setEnter(false);
            $bossbar->removePlayer($player);
            $player->sendTitle(ChatUtil::fixColors(ConfigUtil::TITLE), ChatUtil::fixColors($title), 0, 60, 0);

        } else if ($guildManager->guildExistsAtPosition($player->getPosition()) && $user->isEnter()) {
            $heart = $guild->getHeartPosition();

            $playerDistance = $player->getPosition()->asVector3()->withComponents($player->getPosition()->getFloorX(), $heart->getFloorY(), $player->getPosition()->getFloorZ())->distance($heart);
            $totalDistance = abs($guild->getPosition1()->getFloorX() - $heart->getFloorX());

            $bossbar->setPercentage(($totalDistance - $playerDistance) / $totalDistance);
        }
    }
}