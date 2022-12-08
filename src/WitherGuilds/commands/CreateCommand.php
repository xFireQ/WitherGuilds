<?php

namespace WitherGuilds\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\Server;
use WitherGuilds\Main;
use WitherGuilds\utils\ChatUtil;

class CreateCommand extends Command {

    public function __construct(){
        parent::__construct("create", "Komenda do zalozenia gildii", null, ["zaloz"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$sender instanceof Player) {
            $sender->sendMessage(ChatUtil::format("Nie mozesz uzyc tej komendy tutaj!"));
            return;
        }

        if (count($args) < 1) {
            $sender->sendMessage(ChatUtil::format("Uzycie to: &6/zaloz &8(&6tag&8<&61-4&8>) (&6nazwa&8<&61-30&8>)"));
            return;
        }

        if (strlen($args[0]) < 1 || strlen($args[0]) > 4 || strlen($args[1]) < 1 || strlen($args[1]) > 30) {
            $sender->sendMessage(ChatUtil::format("Podano zbyt krotki lub dlugi tag / nazwe gildii"));
            return;
        }

        if (Main::getInstance()->getGuildManager()->guildExists($args[0])) {
            $sender->sendMessage(ChatUtil::format("Podana gildia juz istnieje!"));
            return;
        }

        $user = Main::getInstance()->getUserManager()->getUser($sender->getName());

        Main::getInstance()->getGuildManager()->createGuild($args[0], $args[1], $user);
        Server::getInstance()->broadcastMessage(ChatUtil::format("Gracz &6{$sender->getName()} &7zalozyl gildie &6{$args[0]} &8- &6{$args[1]}&7."));

    }
}