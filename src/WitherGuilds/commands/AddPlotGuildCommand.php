<?php

namespace WitherGuilds\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\Server;
use WitherGuilds\Main;
use WitherGuilds\utils\ChatUtil;
use WitherGuilds\utils\ConfigUtil;

class AddPlotGuildCommand extends Command {

    public function __construct(){
        parent::__construct("powieksz", "Powieksza gildie", null, []);
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

        $gSize = $user->getGuild()->getCurrentSize()+10;

        if ($gSize >= ConfigUtil::MAX_SIZE) {
            $sender->sendMessage(ChatUtil::format("Gildia jest powiekszona makysymalnie!"));
            return;
        }

        if (!$sender->getInventory()->contains(VanillaItems::EMERALD()->setCount(64))) {
            $sender->sendMessage(ChatUtil::format("Aby powiekszyc gildie potrzebujesz 64 emeraldow!"));
            return;
        }

        $user->getGuild()->setCurrentSize($gSize);
        Main::getInstance()->getGuildManager()->addPlotGuild($user->getGuild(), $gSize);
        $sender->getInventory()->removeItem(VanillaItems::EMERALD()->setCount(64));
        $sender->sendMessage(ChatUtil::format("Powiekszono gildie!"));
    }
}