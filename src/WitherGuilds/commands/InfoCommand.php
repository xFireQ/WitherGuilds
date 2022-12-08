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
use WitherGuilds\utils\ChatUtil;

class InfoCommand extends Command {

    public function __construct(){
        parent::__construct("info", "Pokazuje informacje o gildii", null, ["informacje"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (empty($args)) {
            $user = Main::getInstance()->getUserManager()->getUser($sender->getName());

            if ($user->getGuild() === null) {
                $sender->sendMessage(ChatUtil::format("Uzycie: &6/info &8(&6tag&8)"));
                return;
            }

            $this->sendInfoGuild($user->getGuild(), $sender);
            return;
        }

        if (Main::getInstance()->getGuildManager()->guildExists($args[0])) {
            $this->sendInfoGuild(Main::getInstance()->getGuildManager()->getGuildByTag($args[0]), $sender);
        } else {
            $sender->sendMessage(ChatUtil::format("Gildia o podanym tagu nie istnieje!"));
        }
    }

    private function sendInfoGuild(Guild $guild, CommandSender $sender) : void {
        $sender->sendMessage(ChatUtil::fixColors("&8".str_repeat("=", 10)." [ &6&lWITHERHC &r&8] ".str_repeat("=", 10)));
        $sender->sendMessage(ChatUtil::fixColors("&8» &7Gildia: &6".$guild->getTag()." &8- &6".$guild->getName()));
        $sender->sendMessage(ChatUtil::fixColors("&8» &7Lider: &6".$guild->getLeader()->getName()));
        $sender->sendMessage(ChatUtil::fixColors("&8".str_repeat("=", 10)." [ &6&lWITHERHC &r&8] ".str_repeat("=", 10)));
    }
}