<?php

namespace WitherGuilds\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use WitherGuilds\Main;
use WitherGuilds\user\UserManager;
use WitherGuilds\utils\ChatUtil;

class JoinCommand extends Command
{

    public function __construct() {
        parent::__construct("dolacz", "Dolacza do gildii", null, ["join"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (empty(UserManager::$invitationUsers[$sender->getName()])) {
            $sender->sendMessage(ChatUtil::format("Nie posiadasz zaproszen do gildii!"));
            return;
        }

        if (empty($args)) {
            $sender->sendMessage(ChatUtil::format("&fUzycie to /dolacz [TAG]"));
            return;
        }

        $user = Main::getInstance()->getUserManager()->getUser($sender->getName());

        if ($user->getGuild() !== null) {
            $sender->sendMessage(ChatUtil::format("jestes juz w gildii!"));
            return;
        }

        foreach (UserManager::$invitationUsers as $user => $array) {
            if ($args[0] === $array["guild"]) {
                if ($sender->getName() === $user) {
                    $user = Main::getInstance()->getUserManager()->getUser($sender->getName());

                    if ($array["time"] < time()-30) {
                        $sender->sendMessage(ChatUtil::format("Twoje zaproszenie wygaslo!"));
                        return;
                    }

                    $guild = Main::getInstance()->getGuildManager()->getGuildByTag($array["guild"]);

                    if ($user->getGuild() !== null)
                        return;

                    $guild->addMember($user->getName());
                    $user->setGuild($guild);
                    Server::getInstance()->broadcastMessage(ChatUtil::format("&fGracz &6".$sender->getName()." &fdolaczyl do gildii &6".$guild->getTag()."&f!"));
                }

            }
        }




    }


}