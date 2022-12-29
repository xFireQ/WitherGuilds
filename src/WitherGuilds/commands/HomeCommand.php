<?php

namespace WitherGuilds\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\Server;
use WitherGuilds\guild\Guild;
use WitherGuilds\guild\GuildManager;
use WitherGuilds\Main;
use WitherGuilds\tasks\BaseTeleportTask;
use WitherGuilds\user\UserManager;
use WitherGuilds\utils\ChatUtil;

class HomeCommand extends Command {

    public function __construct(){
        parent::__construct("baza", "Teleportuje do bazy gildii", null, ["dom"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $user = Main::getInstance()->getUserManager()->getUser($sender->getName());

        if ($user->getGuild() === null) {
            $sender->sendMessage(ChatUtil::format("Nie posiadasz gildii!"));
            return;
        }

        if (isset(UserManager::$teleportTasks[$sender->getName()])) {
            $sender->sendMessage(ChatUtil::format("Juz sie teleportujesz!"));
            return;
        }

        UserManager::$teleportTasks[$sender->getName()] = Main::getInstance()->getScheduler()->scheduleRepeatingTask(new BaseTeleportTask($user->getGuild()->getBasePosition(), $sender), 20);
        $sender->sendMessage(ChatUtil::format("Teleportacja rozgrzewa sie..."));
    }
}