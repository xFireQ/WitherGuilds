<?php

namespace WitherGuilds\listeners\player;

use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\ItemIds;
use pocketmine\Server;
use WitherGuilds\Main;
use WitherGuilds\utils\ChatUtil;
use WitherGuilds\utils\ConfigUtil;

class PlayerInteractListener implements Listener {

    public function onBreakHeart(PlayerInteractEvent $event) {
        $block = $event->getBlock();
        $player = $event->getPlayer();

        $guild = Main::getInstance()->getGuildManager()->getGuildAtPosition($block->getPosition());

        if ($block->getId() === ConfigUtil::HEART_ID) {
            $guild = Main::getInstance()->getGuildManager()->getGuildAtPosition($block->getPosition());

            if ($guild !== null) {
                if (Main::getInstance()->getGuildManager()->isHeart($block)) {
                    $event->cancel();
                    $user = Main::getInstance()->getUserManager()->getUser($player->getName());

                    if ($user === null) return;

                    if ($user->getGuild() === null) {
                        $player->sendMessage(ChatUtil::format("Nie posiadasz wlasnej gildii!"));
                        return;
                    }

                    if ($user->getGuild()->getTag() === $guild->getTag()) {
                        $player->sendMessage(ChatUtil::format("Nie mozesz podbic swojej gildii!"));
                        return;
                    }

                    if (!$guild->canConquer()) {
                        $player->sendMessage(ChatUtil::format("Ta gildia posiada ochrone!"));
                        return;
                    }

                    if ($guild->getHp() === 1) {
                        $guild->setHP($guild->getHp()-1);
                        Main::getInstance()->getGuildManager()->deleteGuild($guild);
                        Server::getInstance()->broadcastMessage(ChatUtil::fixColors("&fGildia &6{$guild->getTag()} &fzostala podbita przez [&6{$user->getGuild()->getTag()}&f] &6{$player->getName()}&f!"));
                        return;
                    }

                    if ($user->getGuild()->getHp() < 5)
                        $user->getGuild()->setHP($user->getGuild()->getHp()+1);

                    $guild->setHP($guild->getHp()-1);
                    $adate = date_create(date("G:i:s"));
                    date_add($adate,date_interval_create_from_date_string("1 days"));
                    $adate =  date_format($adate,"d.m.Y H:i:s");
                    $guild->setConquerDate($adate);
                    Server::getInstance()->broadcastMessage(ChatUtil::fixColors("&fGildia &6{$user->getGuild()->getTag()} &fodebrala 1 serce gildii &6{$guild->getTag()}"));


                }
            }
        }

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