<?php

namespace WitherGuilds\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use WitherGuilds\guild\GuildManager;
use WitherGuilds\Main;
use WitherGuilds\utils\ChatUtil;

class WarCommand extends Command {

    public function __construct(){
        parent::__construct("war", "Zaprasza na walke", null, ["walka"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        $user = Main::getInstance()->getUserManager()->getUser($sender->getName());

        if ($user->getGuild() === null) {
            $sender->sendMessage(ChatUtil::format("Nie posiadasz gildii!"));
            return;
        }

        $heartPos = $user->getGuild()->getHeartPosition();
        $guildTag = $user->getGuild()->getTag();

        if (isset(GuildManager::$wars[$guildTag])) {
            if (GuildManager::$wars[$guildTag] > time()-120) {
                $sender->sendMessage(ChatUtil::format("Zbyt szybko uzywasz tej komendy!"));
                return;
            } else {
                unset(GuildManager::$wars[$guildTag]);
            }
        }

        GuildManager::$wars[$guildTag] = time();
        Server::getInstance()->broadcastMessage(ChatUtil::fixColors("&8> &fGildia&6 ".$guildTag."&f zaprasza na walke.\n&8> &fKordy: &6X: &f".$heartPos->getFloorX()." &6Z: &f".$heartPos->getFloorZ()."&f!"));
    }
}