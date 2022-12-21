<?php

namespace WitherGuilds\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\Server;
use WitherGuilds\Main;
use WitherGuilds\utils\ChatUtil;

class DeleteCommand extends Command {

    public function __construct(){
        parent::__construct("delete", "Usuwa gildie", null, ["usun"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$sender instanceof Player) {
            $sender->sendMessage(ChatUtil::format("Nie mozesz uzyc tej komendy tutaj!"));
            return;
        }

        $user = Main::getInstance()->getUserManager()->getUser($sender->getName());

        if ($user->getGuild() === null) {
            $sender->sendMessage(ChatUtil::format("Nie posiadasz gildii!"));
            return;
        }

        if ($user->getGuild()->getLeader()->getName() !== $sender->getName()) {
            $sender->sendMessage(ChatUtil::format("Nie jestes liderem tej gildii!"));
            return;
        }

        Server::getInstance()->broadcastMessage(ChatUtil::format("Gildia &6{$user->getGuild()->getTag()} &fzostala usunieta przez &6{$sender->getName()}&f."));
        Main::getInstance()->getGuildManager()->deleteGuild($user->getGuild());

    }
}