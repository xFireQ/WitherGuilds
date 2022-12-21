<?php

namespace WitherGuilds\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use WitherGuilds\Main;
use WitherGuilds\user\UserManager;
use WitherGuilds\utils\ChatUtil;

class KickCommand extends Command
{

    public function __construct() {
        parent::__construct("kick", "Wyrzuca z gildii", null, ["wyrzuc"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (empty($args)) {
            $sender->sendMessage(ChatUtil::format("&fUzycie to /wyrzuc [NICK]"));
            return;
        }

        $user = Main::getInstance()->getUserManager()->getUser($sender->getName());

        if ($user->getGuild() === null) {
            $sender->sendMessage(ChatUtil::format("Nie jestes w gildii!"));
            return;
        }

        if ($args[0] === $sender->getName())
            return;

        $g = $user->getGuild();

        if ($g->getLeader()->getName() === $sender->getName()) {
            foreach ($g->getMembers() as $member) {
                if ($member === $args[0]) {
                    $user = Main::getInstance()->getUserManager()->getUser($member);

                    if ($user === null)
                        return;

                    $user->setGuild(null);
                    $g->removeMember($member);
                    $sender->sendMessage(ChatUtil::format("Wyrzucono gracza z gildii!"));
                    Server::getInstance()->broadcastMessage(ChatUtil::format("Gracz &6".$member." &fzostal wyrzucony z gildii &6".$g->getTag()));
                    return;
                }
            }
        } else {
            $sender->sendMessage(ChatUtil::format("Nie jestes liderem tej gildiI!"));
        }

        $sender->sendMessage(ChatUtil::format("Ten gracz nie jest w twojej gildii!"));


    }


}