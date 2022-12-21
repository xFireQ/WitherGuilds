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

class FfCommand extends Command {

    public function __construct(){
        parent::__construct("ff", "Zmienia pvp w gildii", null, ["pvp"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        $user = Main::getInstance()->getUserManager()->getUser($sender->getName());

        if ($user->getGuild() === null) {
            $sender->sendMessage(ChatUtil::format("Nie posiadasz gildii!"));
            return;
        }

        if ($user->getGuild()->getLeader()->getName() === $sender->getName()) {
            $this->switchPvP($user->getGuild());
            $sender->sendMessage(ChatUtil::format($user->getGuild()->isEnablePvp() ? "Wylaczono pvp w gildii!" : "Wlaczono pvp w gildii!"));
        }

        return;
    }

    private function switchPvP(Guild $guild) {
        if ($guild->isEnablePvp())
            $guild->setPvp(false);
        else
            $guild->setPvp(true);
    }


}