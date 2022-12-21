<?php

namespace WitherGuilds\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use WitherGuilds\Main;
use WitherGuilds\user\UserManager;
use WitherGuilds\utils\ChatUtil;

class InvitePlayerCommand extends Command
{

    public function __construct() {
        parent::__construct("zapros", "Zaprasza do gildii", null, ["invite"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (empty($args)) {
            $sender->sendMessage(ChatUtil::format("Uzycie to: &6/zapros &8(&6nick&8)"));
            return;
        }

        $senderUser = Main::getInstance()->getUserManager()->getUser($sender->getName());
        $playerUser = Main::getInstance()->getUserManager()->getUser($args[0]);

        if ($playerUser === null || $playerUser->getPlayer() === null) {
            $sender->sendMessage(ChatUtil::format("Podany user nie jest online!"));
            return;
        }

        if ($sender->getName() === $playerUser->getName()) {
            $sender->sendMessage(ChatUtil::format("Nie mozesz zaprosic siebie samego!"));
            return;
        }

        if ($senderUser->getGuild() === null) {
            $sender->sendMessage(ChatUtil::format("Nie posiadasz gildii!"));
            return;
        }


        if ($playerUser->getGuild() !== null) {
            $sender->sendMessage(ChatUtil::format("Ten user posiada juz gildie!"));
            return;
        }


        UserManager::$invitationUsers[$playerUser->getName()] = [
            "time" => time(),
            "guild" => $senderUser->getGuild()->getTag()
        ];
        $sender->sendMessage(ChatUtil::format("&aPomyslnie zaproszono tego gracza do gildii!"));
        $playerUser->getPlayer()->sendMessage(ChatUtil::format("&fZostales zaproszony do gildii &6".$senderUser->getGuild()->getTag()."&f, aby dolaczyc wpisz &6/dolacz &f[&^TAG&f]"));
    }
}