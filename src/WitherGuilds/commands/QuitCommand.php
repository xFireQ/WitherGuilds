<?php

namespace WitherGuilds\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use WitherGuilds\Main;
use WitherGuilds\utils\ChatUtil;

class QuitCommand extends Command {

    public function __construct() {
        parent::__construct("opusc", "Opuszcza gildie", null, ["quit"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        $senderUser = Main::getInstance()->getUserManager()->getUser($sender->getName());

        if ($senderUser->getGuild() === null) {
            $sender->sendMessage(ChatUtil::format("Nie posiadasz gildii!"));
            return;
        }

        if ($senderUser->getGuild()->getLeader()->getName() === $senderUser->getName()) {
            $sender->sendMessage(ChatUtil::format("Jestes liderem tej gildii!"));
            return;
        }

        Server::getInstance()->broadcastMessage(ChatUtil::format("&6Gracz &f".$sender->getName()." &6opuscil gildie &f".$senderUser->getGuild()->getTag()));
        $senderUser->getGuild()->removeMember($sender->getName());
        $senderUser->setGuild(null);
    }


}