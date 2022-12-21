<?php

namespace WitherGuilds\listeners\entity;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use WitherGuilds\Main;

class EntityDamageListener implements Listener {

    public function damaage(EntityDamageEvent $event) {
        if ($event instanceof EntityDamageByEntityEvent) {
            $damager = $event->getDamager();
            $entity = $event->getEntity();

            if ($damager instanceof Player && $entity instanceof Player) {
                $damagerUser = Main::getInstance()->getUserManager()->getUser($damager->getName());
                $entityUser = Main::getInstance()->getUserManager()->getUser($entity->getName());

                if ($damagerUser->getGuild() === null || $entityUser->getGuild() === null)
                    return;

                if($damagerUser->getGuild()->getTag() === $entityUser->getGuild()->getTag()) {
                    if (!$damagerUser->getGuild()->isEnablePvp())
                        return;

                    $event->cancel();
                }
            }
        }
    }
}