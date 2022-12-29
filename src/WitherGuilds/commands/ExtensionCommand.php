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

class ExtensionCommand extends Command {

    public function __construct(){
        parent::__construct("przedluz", "Przedluza waznosc gildie", null, []);
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


        if (!$sender->getInventory()->contains(VanillaItems::EMERALD()->setCount(64))) {
            $sender->sendMessage(ChatUtil::format("Aby powiekszyc gildie potrzebujesz 64 emeraldow!"));
            return;
        }

        $date = date_create($user->getGuild()->getExpiryDate());
        date_add($date,date_interval_create_from_date_string("1 days"));
        $date =  date_format($date,"d.m.Y H:i:s");

        $user->getGuild()->setExpiryDate($date);
        $sender->sendMessage(ChatUtil::format("Przedluzono waznosc gildii."));
        $sender->getInventory()->removeItem(VanillaItems::EMERALD()->setCount(64));
    }
}