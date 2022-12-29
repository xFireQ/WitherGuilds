<?php

namespace WitherGuilds\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use WitherGuilds\Main;
use WitherGuilds\utils\ChatUtil;

class SetHomeCommand extends Command {

    public function __construct(){
        parent::__construct("ustawbaze", "Ustawia baze gildii", null, ["ustawdom"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        $user = Main::getInstance()->getUserManager()->getUser($sender->getName());

        if ($user->getGuild() === null) {
            $sender->sendMessage(ChatUtil::format("Nie posiadasz gildii!"));
            return;
        }

        if ($user->getGuild()->getLeader()->getName() !== $sender->getName()) {
            $sender->sendMessage(ChatUtil::format("Nie jesteds liderem tej gildii!"));
            return;
        }

        $user->getGuild()->setBasePosition($sender->getPosition());
        $sender->sendMessage(ChatUtil::format("Ustawiono baze gildii!"));
    }
}